// Shared mock data for both directions
const navItems = [
  { id: 'dashboard',  label: 'Dashboard',       icon: 'Dashboard' },
  { id: 'orders',     label: 'Orders',          icon: 'Bag',       count: 12 },
  { id: 'downloads',  label: 'Downloads',       icon: 'Download',  count: 3 },
  { id: 'addresses',  label: 'Addresses',       icon: 'Pin' },
  { id: 'account',    label: 'Account details', icon: 'User' },
];

const orders = [
  { id: '#GZ-24891', name: 'Pearl Baby Lariat',     date: '24 Apr 2026', total: '₹2,499', qty: 1, status: 'shipping',   eta: 'Arrives Apr 29' },
  { id: '#GZ-24762', name: 'Tarot Charm Hoops',     date: '11 Apr 2026', total: '₹1,899', qty: 2, status: 'delivered',  eta: 'Delivered' },
  { id: '#GZ-24655', name: 'Stardust Chain Anklet', date: '02 Apr 2026', total: '₹1,299', qty: 1, status: 'delivered',  eta: 'Delivered' },
  { id: '#GZ-24580', name: 'Cherry Pit Pendant',    date: '20 Mar 2026', total: '₹3,200', qty: 1, status: 'processing', eta: 'Ships soon' },
  { id: '#GZ-24411', name: 'Y2K Butterfly Ring Set',date: '08 Mar 2026', total: '₹2,100', qty: 3, status: 'delivered',  eta: 'Delivered' },
];

const downloads = [
  { name: 'Care & Cleaning Guide.pdf',     order: '#GZ-24891', expires: 'Never',         remaining: '∞',     ext: 'PDF' },
  { name: 'Authenticity Certificate.pdf',  order: '#GZ-24762', expires: '12 Apr 2027',   remaining: '5 / 5', ext: 'PDF' },
  { name: 'Custom Engraving Artwork.png',  order: '#GZ-24580', expires: '30 days',       remaining: '2 / 3', ext: 'PNG' },
];

const addresses = [
  { tag: 'Home',   name: 'Nisha Bhattacharya', lines: ['12 Lavender Lane, Indiranagar', 'Bengaluru, Karnataka 560038', 'India · +91 98765 43210'], default: true },
  { tag: 'Work',   name: 'Nisha Bhattacharya', lines: ['Floor 4, Tinder Towers',         'Koramangala, Bengaluru 560034',  'India · +91 98765 43210'], default: false },
  { tag: 'Mum\u2019s', name: 'Sonia Bhattacharya', lines: ['Rose Apartments, Block C', 'Salt Lake, Kolkata 700091',      'India · +91 98123 45678'], default: false },
];

const wishlist = [
  { name: 'Champagne Halo Studs',  price: '₹3,499' },
  { name: 'Cosmic Drop Earrings',  price: '₹2,799' },
  { name: 'Daisy Chain Bracelet',  price: '₹1,599' },
  { name: 'Snake Bone Necklace',   price: '₹4,200' },
];

const user = {
  first: 'Nisha',
  last: 'Bhattacharya',
  display: 'nishaglows',
  email: 'nisha@example.com',
  phone: '+91 98765 43210',
  joined: 'Jan 2024',
  points: 1280,
  pointsToNext: 720,
  tier: 'Pearl',
  nextTier: 'Diamond',
};

window.GenzeeData = { navItems, orders, downloads, addresses, wishlist, user };
