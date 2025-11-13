'use client';

import { useEffect, useState } from 'react';
import { statsApi } from '@/lib/api';
import { useAuth } from '@/hooks/useAuth';

interface DashboardStats {
  revenue: {
    total: string;
    percentageChange: number;
  };
  orders: {
    total: number;
    avgOrderRevenue: string;
    percentageChange: number;
  };
  subscriptions: {
    total: number;
    percentageChange: number;
  };
}

export default function RevenueSection() {
  const { isAuthenticated, loading: authLoading } = useAuth();
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (authLoading || !isAuthenticated) return;

    const fetchStats = async () => {
      try {
        const data = await statsApi.dashboard();
        setStats(data);
      } catch (error) {
        console.error('Error fetching stats:', error);
        // Fallback to default values if API fails
        setStats({
          revenue: { total: '90,239.00', percentageChange: 0 },
          orders: { total: 320, avgOrderRevenue: '1,080', percentageChange: -4 },
          subscriptions: { total: 22, percentageChange: 15 },
        });
      } finally {
        setLoading(false);
      }
    };

    fetchStats();
  }, [isAuthenticated, authLoading]);

  if (authLoading || loading) {
    return (
      <div className="mb-10">
        <div className="flex items-center justify-center h-64">
          <div className="text-gray-500">Chargement...</div>
        </div>
      </div>
    );
  }

  const revenue = stats?.revenue || { total: '90,239.00', percentageChange: 0 };
  const orders = stats?.orders || { total: 320, avgOrderRevenue: '1,080', percentageChange: -4 };
  const subscriptions = stats?.subscriptions || { total: 22, percentageChange: 15 };

  return (
    <div className="mb-10">
      <div className="flex items-center justify-between mb-6 p-2">
        <div>
          <h1 className="text-5xl font-bold mb-2">Your total revenue</h1>
          <p className="text-5xl font-bold bg-gradient-to-r from-purple-500 via-orange-500 to-pink-400 bg-clip-text text-transparent">
            ${revenue.total}
          </p>
        </div>
        <div className="flex gap-3">
          <button className="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-lg hover:bg-gray-50">
            <svg className="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span className="text-md font-medium">Select Dates</span>
          </button>
          <button className="flex items-center gap-2 px-4 py-2 border-2 border-gray-200 rounded-lg hover:bg-gray-50">
            <svg className="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span className="text-md font-medium">Filters</span>
          </button>
        </div>
      </div>

      <div className="grid grid-cols-3 gap-0">
        {/* New Subscriptions */}
        <div className="bg-white p-6 ml-3 border-l-2 border-t-2 border-b-2 border-gray-200 rounded-l-xl">
          <h3 className="text-lg font-semibold text-gray-800 mb-8">New subscriptions</h3>
          <div className="flex items-end justify-between mb-4">
            <div>
              <div className="flex items-center gap-3 mb-1">
                <span className="text-3xl font-bold">{subscriptions.total}</span>
                <span className={`flex items-center gap-2 text-md font-semibold ${subscriptions.percentageChange >= 0 ? 'text-green-600' : 'text-orange-600'}`}>
                  <span className={`flex items-center justify-center w-6 h-6 rounded-full ${subscriptions.percentageChange >= 0 ? 'bg-[#C8FDB9] bg-opacity-80' : 'bg-[#FEECCE] bg-opacity-90'}`}>
                    {subscriptions.percentageChange >= 0 ? (
                      <svg className="w-4 h-4 text-[#187F15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 10l7-7m0 0l7 7m-7-7v18" />
                      </svg>
                    ) : (
                      <svg className="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                      </svg>
                    )}
                  </span>
                  {Math.abs(subscriptions.percentageChange)}%
                </span>
              </div>
              <p className="text-sm font-semibold text-gray-500">compared to last week</p>
            </div>
            <div className="w-32 h-16">
              <svg viewBox="0 0 100 50" className="w-full h-full">
                <path
                  d="M 0,40 L 20,35 L 40,30 L 60,25 L 80,15 L 100,10"
                  fill="none"
                  stroke="#a855f7"
                  strokeWidth="2"
                />
                <path
                  d="M 0,40 L 20,35 L 40,30 L 60,25 L 80,15 L 100,10 L 100,50 L 0,50 Z"
                  fill="url(#gradient1)"
                  opacity="0.2"
                />
                <defs>
                  <linearGradient id="gradient1" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stopColor="#a855f7" />
                    <stop offset="100%" stopColor="#ffffff" />
                  </linearGradient>
                </defs>
              </svg>
            </div>
          </div>
        </div>

        {/* New Orders */}
        <div className="bg-white p-6 border-2 border-gray-200 border-l-2 border-r-2">
          <h3 className="text-lg font-semibold text-gray-800 mb-8">New orders</h3>
          <div className="flex items-end justify-between mb-4">
            <div>
              <div className="flex items-center gap-3 mb-1">
                <span className="text-3xl font-bold">{orders.total}</span>
                <span className={`flex items-center gap-2 text-md font-semibold ${orders.percentageChange >= 0 ? 'text-green-600' : 'text-[#f97316]'}`}>
                  <span className={`flex items-center justify-center w-6 h-6 rounded-full ${orders.percentageChange >= 0 ? 'bg-[#C8FDB9] bg-opacity-80' : 'bg-[#FEECCE] bg-opacity-90'}`}>
                    {orders.percentageChange >= 0 ? (
                      <svg className="w-4 h-4 text-[#187F15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 10l7-7m0 0l7 7m-7-7v18" />
                      </svg>
                    ) : (
                      <svg className="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                      </svg>
                    )}
                  </span>
                  {Math.abs(orders.percentageChange)}%
                </span>
              </div>
              <p className="text-sm font-semibold text-gray-500">compared to last week</p>
            </div>
            <div className="w-32 h-16">
              <svg viewBox="0 0 100 50" className="w-full h-full">
                <path
                  d="M 0,20 L 20,25 L 40,22 L 60,28 L 80,35 L 100,30"
                  fill="none"
                  stroke="#f97316"
                  strokeWidth="2"
                />
                <path
                  d="M 0,20 L 20,25 L 40,22 L 60,28 L 80,35 L 100,30 L 100,50 L 0,50 Z"
                  fill="url(#gradient2)"
                  opacity="0.2"
                />
                <defs>
                  <linearGradient id="gradient2" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stopColor="#f97316" />
                    <stop offset="100%" stopColor="#ffffff" />
                  </linearGradient>
                </defs>
              </svg>
            </div>
          </div>
        </div>

        {/* Average Order Revenue */}
        <div className="bg-white p-6 border-t-2 border-b-2 border-r-2 border-gray-200 rounded-r-xl">
          <h3 className="text-lg font-semibold text-gray-800 mb-8">Avg. order revenue</h3>
          <div className="flex items-end justify-between mb-4">
            <div>
              <div className="flex items-center gap-3 mb-1">
                <span className="text-3xl font-bold">${orders.avgOrderRevenue}</span>
                <span className="flex items-center gap-2 text-md font-semibold text-[#187F15]">
                  <span className="flex items-center justify-center w-6 h-6 rounded-full bg-[#C8FDB9] bg-opacity-90">
                    <svg className="w-4 h-4 text-[#187F15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                  </span>
                  8%
                </span>
              </div>
              <p className="text-sm font-semibold text-gray-500">compared to last week</p>
            </div>
            <div className="w-32 h-16">
              <svg viewBox="0 0 100 50" className="w-full h-full">
                <path
                  d="M 0,35 L 20,38 L 40,32 L 60,28 L 80,20 L 100,15"
                  fill="none"
                  stroke="#a855f7"
                  strokeWidth="2"
                />
                <path
                  d="M 0,35 L 20,38 L 40,32 L 60,28 L 80,20 L 100,15 L 100,50 L 0,50 Z"
                  fill="url(#gradient3)"
                  opacity="0.2"
                />
                <defs>
                  <linearGradient id="gradient3" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stopColor="#a855f7" />
                    <stop offset="100%" stopColor="#ffffff" />
                  </linearGradient>
                </defs>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
