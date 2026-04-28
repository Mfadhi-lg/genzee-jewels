// Lucide-style minimal stroke icons. All take {size=16}
const Ic = {
  Dashboard: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
  ),
  Bag: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
  ),
  Download: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
  ),
  Pin: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
  ),
  User: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
  ),
  Logout: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
  ),
  Search: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
  ),
  Heart: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78Z"/></svg>
  ),
  Plus: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  ),
  Arrow: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
  ),
  Sparkle: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M12 3 13.5 9 19 10.5 13.5 12 12 18 10.5 12 5 10.5 10.5 9Z"/></svg>
  ),
  Truck: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><rect x="1" y="6" width="14" height="11"/><polygon points="15 9 21 9 23 13 23 17 15 17"/><circle cx="6" cy="19" r="2"/><circle cx="18" cy="19" r="2"/></svg>
  ),
  Edit: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
  ),
  Star: ({ size = 16 }) => (
    <svg className="lucide" width={size} height={size} viewBox="0 0 24 24"><polygon points="12 2 15 9 22 9.5 17 14.5 18.5 22 12 18 5.5 22 7 14.5 2 9.5 9 9"/></svg>
  ),
};

window.Ic = Ic;
