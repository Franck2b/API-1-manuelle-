export default function RightSidebar() {
  const recentDocuments = [
    {
      id: 1,
      title: 'Summer Campaign',
      updated: 'Updated 2 minutes ago',
      thumbnail: '📝',
      color: 'bg-purple-100'
    },
    {
      id: 2,
      title: 'Inspiration Notes',
      updated: 'Updated 3 hours ago',
      thumbnail: '🎨',
      color: 'bg-orange-100'
    },
    {
      id: 3,
      title: 'Campaign Moodboard',
      updated: 'Updated 5 hours ago',
      thumbnail: '🖼️',
      color: 'bg-pink-100'
    },
    {
      id: 4,
      title: 'Circular Inspiration',
      updated: 'Updated 8 hours ago',
      thumbnail: '⭕',
      color: 'bg-green-100'
    },
    {
      id: 5,
      title: 'Luxury Campaign Steps',
      updated: 'Updated 8 hours ago',
      thumbnail: '💎',
      color: 'bg-yellow-100'
    },
    {
      id: 6,
      title: 'Untitled',
      updated: 'Updated 2 days ago',
      thumbnail: '📄',
      color: 'bg-gray-100'
    },
    {
      id: 7,
      title: 'Test Note',
      updated: 'Updated 2 days ago',
      thumbnail: '📝',
      color: 'bg-blue-100'
    },
    {
      id: 8,
      title: 'Concepts',
      updated: 'Updated 2 days ago',
      thumbnail: '💡',
      color: 'bg-purple-100'
    }
  ];

  const teamMates = [
    {
      id: 1,
      name: 'Ethan Anderson',
      role: 'Product Manager',
      avatar: 'EA',
      online: true,
      color: 'bg-blue-500'
    },
    {
      id: 2,
      name: 'Simone Daniels',
      role: 'Marketing Manager',
      avatar: 'S',
      online: true,
      color: 'bg-green-400'
    },
    {
      id: 3,
      name: 'Noah Martinez',
      role: 'CRM Manager',
      avatar: 'NM',
      online: true,
      color: 'bg-teal-500'
    },
    {
      id: 4,
      name: 'Olivia Mitchell',
      role: 'Designer',
      avatar: 'OM',
      online: false,
      color: 'bg-orange-500'
    },
    {
      id: 5,
      name: 'Liam Johnson',
      role: 'Designer',
      avatar: 'LJ',
      online: false,
      color: 'bg-gray-700'
    },
    {
      id: 6,
      name: 'Ava Williams',
      role: 'Designer',
      avatar: 'AW',
      online: false,
      color: 'bg-purple-500'
    }
  ];

  return (
    <aside className="w-80 bg-white border-l border-gray-200 overflow-y-auto">
      {/* Recent Documents */}
      <div className="p-6">
        <h3 className="text-sm font-semibold text-gray-500 mb-4">Recent Documents</h3>
        <div className="space-y-2">
          {recentDocuments.map(doc => (
            <a key={doc.id} href="#" className="flex items-center gap-3 p-1 rounded-lg hover:bg-gray-50 transition-colors">
              <div className={`w-10 h-10 ${doc.color} rounded-lg flex items-center justify-center text-xl flex-shrink-0`}>
                {doc.thumbnail}
              </div>
              <div className="flex-1 min-w-0">
                <h4 className="text-sm font-semibold text-gray-900 truncate">{doc.title}</h4>
                <p className="text-xs text-gray-500 font-semibold">{doc.updated}</p>
              </div>
            </a>
          ))}
        </div>
      </div>

      {/* Team Mates */}
      <div className="p-6">
        <h3 className="text-sm font-semibold text-gray-500 mb-4">Team Mates</h3>
        <div className="space-y-5">
          {teamMates.map(mate => (
            <div key={mate.id} className="flex items-center gap-3">
              <div className="relative">
                <div className={`w-10 h-10 ${mate.color} rounded-full flex items-center justify-center text-white text-sm font-medium`}>
                  {mate.avatar}
                </div>
                {mate.online && (
                  <span className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                )}
              </div>
              <div className="flex-1">
                <h4 className="text-sm font-semibold text-gray-900">{mate.name}</h4>
                <p className="text-xs text-gray-500 font-semibold">{mate.role}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </aside>
  );
}

