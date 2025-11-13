const API_BASE = process.env.NEXT_PUBLIC_API_BASE || 'http://localhost:8000';

// Store token in localStorage
export const setAuthToken = (token: string) => {
  if (typeof window !== 'undefined') {
    localStorage.setItem('auth_token', token);
  }
};

export const getAuthToken = (): string | null => {
  if (typeof window !== 'undefined') {
    return localStorage.getItem('auth_token');
  }
  return null;
};

export const removeAuthToken = () => {
  if (typeof window !== 'undefined') {
    localStorage.removeItem('auth_token');
  }
};

// API client
const apiRequest = async (endpoint: string, options: RequestInit = {}, ignoreErrors: number[] = []) => {
  const token = getAuthToken();
  const headers: Record<string, string> = {
    'Content-Type': 'application/json',
    ...(options.headers as Record<string, string> || {}),
  };

  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }

  const response = await fetch(`${API_BASE}${endpoint}`, {
    ...options,
    headers,
    cache: 'no-store', // Désactiver le cache pour toujours récupérer les dernières données
  });

  if (!response.ok) {
    if (response.status === 401) {
      removeAuthToken();
      // Redirect to login or handle unauthorized
    }
    // Don't throw for ignored error codes
    if (ignoreErrors.includes(response.status)) {
      const errorData = await response.json().catch(() => ({}));
      throw { status: response.status, message: errorData.error || response.statusText, data: errorData };
    }
    const errorData = await response.json().catch(() => ({}));
    throw new Error(errorData.error || `API Error: ${response.status} ${response.statusText}`);
  }

  return response.json();
};

// Auth API
export const authApi = {
  login: async (email: string, password: string) => {
    const response = await fetch(`${API_BASE}/api/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });

    if (!response.ok) {
      throw new Error('Login failed');
    }

    const data = await response.json();
    if (data.token) {
      setAuthToken(data.token);
    }
    return data;
  },

  logout: () => {
    removeAuthToken();
  },
};

// Campaigns API
export const campaignsApi = {
  list: async (params?: {
    page?: number;
    limit?: number;
    status?: string;
    platform?: string;
    search?: string;
  }) => {
    const queryParams = new URLSearchParams();
    if (params?.page) queryParams.append('page', params.page.toString());
    if (params?.limit) queryParams.append('limit', params.limit.toString());
    if (params?.status) queryParams.append('status', params.status);
    if (params?.platform) queryParams.append('platform', params.platform);
    if (params?.search) queryParams.append('search', params.search);

    const query = queryParams.toString();
    return apiRequest(`/api/campaigns${query ? `?${query}` : ''}`);
  },

  get: async (id: number) => {
    return apiRequest(`/api/campaigns/${id}`);
  },

  create: async (campaign: any) => {
    return apiRequest('/api/campaigns', {
      method: 'POST',
      body: JSON.stringify(campaign),
    });
  },

  update: async (id: number, campaign: any) => {
    return apiRequest(`/api/campaigns/${id}`, {
      method: 'PUT',
      body: JSON.stringify(campaign),
    });
  },

  delete: async (id: number) => {
    return apiRequest(`/api/campaigns/${id}`, {
      method: 'DELETE',
    });
  },
};

// Stats API
export const statsApi = {
  revenue: async (startDate?: string, endDate?: string) => {
    const params = new URLSearchParams();
    if (startDate) params.append('startDate', startDate);
    if (endDate) params.append('endDate', endDate);
    const query = params.toString();
    return apiRequest(`/api/stats/revenue${query ? `?${query}` : ''}`);
  },

  orders: async (startDate?: string, endDate?: string) => {
    const params = new URLSearchParams();
    if (startDate) params.append('startDate', startDate);
    if (endDate) params.append('endDate', endDate);
    const query = params.toString();
    return apiRequest(`/api/stats/orders${query ? `?${query}` : ''}`);
  },

  subscriptions: async (startDate?: string, endDate?: string) => {
    const params = new URLSearchParams();
    if (startDate) params.append('startDate', startDate);
    if (endDate) params.append('endDate', endDate);
    const query = params.toString();
    return apiRequest(`/api/stats/subscriptions${query ? `?${query}` : ''}`);
  },

  dashboard: async () => {
    return apiRequest('/api/stats/dashboard');
  },
};

