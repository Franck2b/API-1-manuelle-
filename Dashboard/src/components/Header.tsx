'use client';

import { useRouter } from 'next/navigation';
import { authApi } from '@/lib/api';

export default function Header() {
  const router = useRouter();

  const handleLogout = () => {
    authApi.logout();
    router.push('/login');
  };

  return (
    <header className="bg-white px-6 py-5">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-4">
          <nav className="flex items-center gap-2 text-base">
            <a href="#" className="text-gray-500 font-semibold hover:text-gray-700">Campaigns</a>
            <span className="text-gray-400">/</span>
            <span className="text-gray-900 font-semibold">Analytics</span>
          </nav>
        </div>

        <div className="flex items-center gap-4">
          <div className="relative">
            <input
              type="text"
              placeholder="Search"
              className="w-72 pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-gray-500"
            />
            <svg className="w-5 h-5 text-gray-700 absolute left-3 top-2/4 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <kbd className="absolute right-3 top-1/2 -translate-y-1/2 py-0.5 text-xl font-semibold text-gray-500 bg-gray-100">
              ⌘ /
            </kbd>
          </div>

          <button className="relative p-2 hover:bg-gray-100 rounded-lg">
            <svg className="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>

          <button
            onClick={handleLogout}
            className="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            title="Déconnexion"
          >
            Déconnexion
          </button>
        </div>
      </div>
    </header>
  );
}

