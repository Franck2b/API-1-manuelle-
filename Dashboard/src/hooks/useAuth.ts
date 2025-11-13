'use client';

import { useEffect, useState } from 'react';
import { authApi, getAuthToken } from '@/lib/api';

export const useAuth = () => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const initAuth = () => {
      const token = getAuthToken();
      if (token) {
        setIsAuthenticated(true);
      }
      setLoading(false);
    };

    initAuth();
  }, []);

  return { isAuthenticated, loading };
};

