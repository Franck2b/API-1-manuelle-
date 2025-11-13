'use client';

import { useEffect, useState } from 'react';
import { campaignsApi } from '@/lib/api';
import { useAuth } from '@/hooks/useAuth';

interface Collaborator {
  id: number;
  initials: string;
  color: string;
}

interface Campaign {
  id: number;
  platform: string;
  title: string;
  status: string;
  startDate?: string;
  endDate?: string;
  progress?: number;
  lastUpdated: string;
  collaborators: Collaborator[];
}

export default function CampaignsSection() {
  const { isAuthenticated, loading: authLoading } = useAuth();
  const [campaigns, setCampaigns] = useState<{
    draft: Campaign[];
    inProgress: Campaign[];
    archived: Campaign[];
  }>({
    draft: [],
    inProgress: [],
    archived: [],
  });
  const [loading, setLoading] = useState(true);

  const fetchCampaigns = async () => {
    if (authLoading || !isAuthenticated) return;
    
    setLoading(true);
    try {
      // Fetch all campaigns with cache-busting
      const response = await campaignsApi.list({ limit: 100 });
      const allCampaigns = response.items || [];
      console.log('Campaigns récupérées depuis l\'API:', allCampaigns);

      // Format dates and organize by status
      const formattedCampaigns = allCampaigns.map((campaign: any) => ({
        ...campaign,
        startDate: campaign.startDate ? new Date(campaign.startDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : undefined,
        endDate: campaign.endDate ? new Date(campaign.endDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : undefined,
        lastUpdated: campaign.lastUpdated ? new Date(campaign.lastUpdated).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A',
        collaborators: campaign.collaborators?.map((collab: any) => ({
          id: collab.id,
          initials: collab.initials || collab.firstName?.[0] + collab.lastName?.[0] || 'U',
          color: collab.color || 'bg-gray-500',
        })) || [],
      }));

      // Organize by status
      const organized = {
        draft: formattedCampaigns.filter((c: Campaign) => c.status === 'draft'),
        inProgress: formattedCampaigns.filter((c: Campaign) => c.status === 'in_progress'),
        archived: formattedCampaigns.filter((c: Campaign) => c.status === 'archived'),
      };

      setCampaigns(organized);
    } catch (error) {
      console.error('Error fetching campaigns:', error);
      // Fallback to empty arrays
      setCampaigns({ draft: [], inProgress: [], archived: [] });
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCampaigns();
  }, [isAuthenticated, authLoading]);

  const getPlatformIcon = (platform: string) => {
    switch (platform) {
      case 'facebook':
        return (
          <div className="w-10 h-10 rounded-lg flex items-center justify-center">
            <svg className="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
          </div>
        );
      case 'instagram':
        return (
          <div className="w-10 h-10 rounded-lg flex items-center justify-center">
            <svg className="w-8 h-8 text-gradient-to-br from-purple-500 to-pink-500" style={{ color: "url(#instagram-gradient)" }} fill="currentColor" viewBox="0 0 24 24">
              <defs>
                <linearGradient id="instagram-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stopColor="#a855f7" /> 
                  <stop offset="100%" stopColor="#ec4899" /> 
                </linearGradient>
              </defs>
              <path fill="url(#instagram-gradient)" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
          </div>
        );
      case 'google':
        return (
          <div className="w-10 h-10 rounded-lg flex items-center justify-center">
            <svg className="w-8 h-8" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
          </div>
        );
      default:
        return null;
    }
  };

  if (authLoading || loading) {
    return (
      <div>
        <div className="flex items-center justify-center h-64">
          <div className="text-gray-500">Chargement des campagnes...</div>
        </div>
      </div>
    );
  }

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-3xl font-bold">Recent campaigns</h2>
        <a href="#" className="text-base font-bold underline text-gray-900 hover:text-gray-700">View All</a>
      </div>

      <div className="grid grid-cols-3 gap-8">
        {/* Draft Column */}
        <div className="space-y-4">
          <div className="text-md font-semibold text-gray-500 ml-1">
            Draft <span className="ml-2 px-2 py-0.5 bg-gray-100 rounded-full text-sm font-semibold text-black">{campaigns.draft.length}</span>
          </div>
          {campaigns.draft.map(campaign => (
            <div key={campaign.id} className="bg-white rounded-xl border border-gray-200 p-5 min-h-[240px] flex flex-col">
              <div className="flex items-start justify-between mb-4">
                {getPlatformIcon(campaign.platform)}
                <div className="flex -space-x-2">
                  {campaign.collaborators.slice(0, 3).map((collab, idx) => (
                    <div key={idx} className={`w-8 h-8 rounded-full ${collab.color} flex items-center justify-center text-white text-xs font-medium border-2 border-white`}>
                      {collab.initials}
                    </div>
                  ))}
                  {campaign.collaborators.length > 3 && (
                    <div className="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-medium border-2 border-white">
                      +{campaign.collaborators.length - 3}
                    </div>
                  )}
                </div>
              </div>
              <h3 className="text-base font-semibold mb-3 line-clamp-2">{campaign.title}</h3>
              <div className="text-sm text-gray-500 font-semibold space-y-3">
                <p>Start: <span className="text-gray-400 font-normal">Not Started</span></p>
                <div className="w-full bg-gray-200 rounded-full h-1"></div>
                <p>Last updated: <span className="text-gray-900 font-semibold">{campaign.lastUpdated}</span></p>
              </div>
            </div>
          ))}
          
          {campaigns.draft.length === 0 && (
            <div className="text-sm text-gray-400 text-center py-4">Aucune campagne en brouillon</div>
          )}
          
          <button className="text-sm text-gray-500 hover:text-gray-900 font-semibold">
            <span className="text-lg mr-2">+</span>Add campaign
          </button>
        </div>

        {/* In Progress Column */}
        <div className="space-y-4">
          <div className="text-md font-semibold text-gray-500 ml-1">
            In Progress <span className="ml-2 px-2 py-0.5 bg-gray-100 rounded-full text-sm font-semibold text-black">{campaigns.inProgress.length}</span>
          </div>
          {campaigns.inProgress.map(campaign => (
            <div key={campaign.id} className="bg-white rounded-xl border border-gray-200 p-5 min-h-[240px] flex flex-col">
              <div className="flex items-start justify-between mb-4">
                {getPlatformIcon(campaign.platform)}
                <div className="flex -space-x-2">
                  {campaign.collaborators.slice(0, 3).map((collab, idx) => (
                    <div key={idx} className={`w-8 h-8 rounded-full ${collab.color} flex items-center justify-center text-white text-xs font-medium border-2 border-white`}>
                      {collab.initials}
                    </div>
                  ))}
                  {campaign.collaborators.length > 3 && (
                    <div className="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-medium border-2 border-white">
                      +{campaign.collaborators.length - 3}
                    </div>
                  )}
                </div>
              </div>
              <h3 className="text-base font-semibold mb-3 line-clamp-2">{campaign.title}</h3>
              {campaign.progress !== undefined ? (
                <>
                  <div className="space-y-2 text-sm mb-3">
                    <div className="flex justify-between text-gray-500 font-semibold">
                      <span>Start: <span className="font-normal">{campaign.startDate || 'N/A'}</span></span>
                      <span>Ends: <span className="font-normal">{campaign.endDate || 'N/A'}</span></span>
                    </div>
                    <div className="w-full bg-gray-200 rounded-full h-1">
                      <div className="bg-green-500 h-1 rounded-full" style={{ width: `${campaign.progress}%` }}></div>
                    </div>
                  </div>
                  <p className="text-sm text-gray-500 font-semibold">Last updated: <span className="text-gray-900 font-semibold">{campaign.lastUpdated}</span></p>
                </>
              ) : (
                <div className="text-sm text-gray-500 font-semibold space-y-3">
                  <p>Start: <span className="text-gray-400 font-normal">{campaign.startDate || 'N/A'}</span></p>
                  <div className="w-full bg-gray-200 rounded-full h-1"></div>
                  <p>Last updated: <span className="text-gray-900 font-semibold">{campaign.lastUpdated}</span></p>
                </div>
              )}
            </div>
          ))}
          
          {campaigns.inProgress.length === 0 && (
            <div className="text-sm text-gray-400 text-center py-4">Aucune campagne en cours</div>
          )}
          
          <button className="text-sm text-gray-500 hover:text-gray-900 font-semibold">
            <span className="text-lg mr-2">+</span>Add campaign
          </button>
        </div>

        {/* Archived Column */}
        <div className="space-y-4">
          <div className="text-md font-semibold text-gray-500 ml-1">
            Archived <span className="ml-2 px-2 py-0.5 bg-gray-100 rounded-full text-sm font-semibold text-black">{campaigns.archived.length}</span>
          </div>
          {campaigns.archived.map(campaign => (
            <div key={campaign.id} className="bg-white rounded-xl border border-gray-200 p-5 min-h-[240px] flex flex-col">
              <div className="flex items-start justify-between mb-4">
                {getPlatformIcon(campaign.platform)}
                <div className="flex -space-x-2">
                  {campaign.collaborators.slice(0, 3).map((collab, idx) => (
                    <div key={idx} className={`w-8 h-8 rounded-full ${collab.color} flex items-center justify-center text-white text-xs font-medium border-2 border-white`}>
                      {collab.initials}
                    </div>
                  ))}
                  {campaign.collaborators.length > 3 && (
                    <div className="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center text-white text-xs font-medium border-2 border-white">
                      +{campaign.collaborators.length - 3}
                    </div>
                  )}
                </div>
              </div>
              <h3 className="text-base font-semibold mb-3 line-clamp-2">{campaign.title}</h3>
              <div className="text-sm text-gray-500 font-semibold space-y-3">
                <p>Ended: <span className="text-gray-500 font-normal">{campaign.endDate || 'N/A'}</span></p>
                <div className="w-full bg-gray-200 rounded-full h-1"></div>
                <p>Last updated: <span className="text-gray-900 font-semibold">{campaign.lastUpdated}</span></p>
              </div>
            </div>
          ))}
          
          {campaigns.archived.length === 0 && (
            <div className="text-sm text-gray-400 text-center py-4">Aucune campagne archivée</div>
          )}
          
          <button className="text-sm text-gray-500 hover:text-gray-900 font-semibold">
            <span className="text-lg mr-2">+</span>Add campaign
          </button>
        </div>
      </div>
    </div>
  );
}
