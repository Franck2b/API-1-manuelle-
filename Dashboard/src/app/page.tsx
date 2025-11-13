'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Sidebar from "@/components/Sidebar";
import Header from "@/components/Header";
import RevenueSection from "@/components/RevenueSection";
import CampaignsSection from "@/components/CampaignsSection";
import RightSidebar from "@/components/RightSidebar";
import { useAuth } from '@/hooks/useAuth';
import { getAuthToken } from '@/lib/api';

export default function Home() {
  const router = useRouter();
  const { isAuthenticated, loading } = useAuth();

  useEffect(() => {
    if (!loading && !isAuthenticated && !getAuthToken()) {
      router.push('/login');
    }
  }, [isAuthenticated, loading, router]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <div className="text-gray-500">Chargement...</div>
      </div>
    );
  }

  if (!isAuthenticated) {
    return null;
  }

  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar />
      
      <div className="flex-1 flex flex-col overflow-hidden">
        <Header />
        
        <main className="flex-1 overflow-y-auto p-8">
          <RevenueSection />
          <CampaignsSection />
        </main>
      </div>
      
      <RightSidebar />
    </div>
  );
}

