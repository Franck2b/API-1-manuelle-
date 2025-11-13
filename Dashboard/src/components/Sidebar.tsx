export default function Sidebar() {
  return (
    <aside className="w-72 bg-white border-r border-gray-200 flex flex-col">
      {/* Logo */}
      <div className="p-6 flex items-center justify-between">
        <div className="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center">
          <svg className="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
          </svg>
        </div>
        <button className="p-2 hover:bg-gray-100 rounded-lg transition-colors">
          <svg className="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 4H5a2 2 0 00-2 2v14a2 2 0 002 2h4M9 4h10a2 2 0 012 2v14a2 2 0 01-2 2H9M9 4v16" />
          </svg>
        </button>
      </div>

      {/* Favorites Section */}
      <div className="px-6 mb-12 mt-6">
        <h3 className="text-base font-semibold text-gray-500 mb-1">Favorites</h3>
        <nav className="space-y-1">
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span className="font-bold">Technical Docs</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span className="font-bold">Campaign Guidliness</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span className="font-bold">Important Rules</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <span className="font-bold">Onboarding</span>
          </a>
        </nav>
      </div>

      {/* Main Menu Section */}
      <div className="px-6 flex-1">
        <h3 className="text-base font-semibold text-gray-500 mb-1">Main Menu</h3>
        <nav className="space-y-1">
          <a href="#" className="flex items-center gap-1 px -1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span className="font-bold">Dashboard</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-900 bg-gray-100 rounded-lg font-medium">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span className="font-bold">Campaigns</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span className="font-bold">Chat</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span className="font-bold">Support Center</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span className="font-bold">Leads</span>
          </a>
          <a href="#" className="flex items-center gap-1 px-1 py-2 text-base text-gray-700 hover:bg-gray-50 rounded-lg">
            <svg className="w-7 h-7 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            <span className="font-bold">Archive</span>
          </a>
        </nav>
      </div>

      {/* Bottom Section */}
      <div className="p-6">
        {/* Extension Banner */}
        <div className="bg-gradient-to-r from-green-200 via-lime-200 to-yellow-200 rounded-2xl p-4 mb-6">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
              <svg className="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" />
              </svg>
            </div>
            <div className="flex flex-col">
              <h4 className="text-sm font-semibold leading-tight">Get the extension</h4>
              <a href="#" className="text-sm underline hover:no-underline">Install Now</a>
            </div>
          </div>
        </div>

        {/* User Profile */}
        <div className="flex items-center gap-3">
          <div className="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-purple-500 flex items-center justify-center text-white font-semibold">
            AD
          </div>
          <div className="flex-1">
            <h4 className="text-md font-semibold">Amanda Davids</h4>
            <p className="text-sm text-gray-500 font-semibold">Administrator</p>
          </div>
          <button className="p-2 hover:bg-gray-100 rounded-lg">
            <svg className="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>
        </div>
      </div>
    </aside>
  );
}

