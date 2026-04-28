// shared.jsx — Genzee Jewels shared components, data, and SVG product placeholders

// ───────────────────────────────────────────────────────────
// PRODUCT DATA (INR, all under ₹499 — impulse-first)
// ───────────────────────────────────────────────────────────
const PRODUCTS = [
  { id:'p1', name:'Butter Heart Huggies', cat:'Earrings', price:299, mrp:599, rating:4.8, reviews:1284, stock:3, tag:'BESTSELLER', color:'#FFE14A', shape:'heart', tone:'gold' },
  { id:'p2', name:'Pixie Pearl Drops', cat:'Earrings', price:249, mrp:499, rating:4.7, reviews:892, stock:12, tag:'NEW', color:'#FAF7F1', shape:'drop', tone:'silver' },
  { id:'p3', name:'Cherry Bomb Studs', cat:'Earrings', price:199, mrp:399, rating:4.9, reviews:2103, stock:5, tag:'🔥 TRENDING', color:'#FF2D6F', shape:'cherry', tone:'gold' },
  { id:'p4', name:'Y2K Chrome Hoops', cat:'Earrings', price:349, mrp:699, rating:4.6, reviews:567, stock:8, tag:null, color:'#C9C9D6', shape:'hoop', tone:'silver' },
  { id:'p5', name:'Starburst Choker', cat:'Necklaces', price:449, mrp:899, rating:4.8, reviews:734, stock:2, tag:'LOW STOCK', color:'#C9B6FF', shape:'star', tone:'gold' },
  { id:'p6', name:'Layered Dainty Chain', cat:'Necklaces', price:399, mrp:799, rating:4.9, reviews:1456, stock:15, tag:'BESTSELLER', color:'#FFE14A', shape:'chain', tone:'gold' },
  { id:'p7', name:'Pearl Baby Lariat', cat:'Necklaces', price:299, mrp:599, rating:4.7, reviews:523, stock:9, tag:null, color:'#F4EFE6', shape:'lariat', tone:'silver' },
  { id:'p8', name:'Bubble Ring Set (3pc)', cat:'Rings', price:249, mrp:599, rating:4.8, reviews:1122, stock:6, tag:'SET', color:'#FF7A3D', shape:'rings', tone:'gold' },
  { id:'p9', name:'Evil Eye Signet', cat:'Rings', price:199, mrp:399, rating:4.9, reviews:876, stock:4, tag:null, color:'#3B82F6', shape:'signet', tone:'silver' },
  { id:'p10', name:'Daisy Dreamer Ring', cat:'Rings', price:179, mrp:349, rating:4.6, reviews:445, stock:20, tag:'NEW', color:'#B8F2C9', shape:'daisy', tone:'gold' },
  { id:'p11', name:'Paperclip Stack Bracelet', cat:'Bracelets', price:329, mrp:649, rating:4.8, reviews:678, stock:7, tag:null, color:'#FFE14A', shape:'paperclip', tone:'gold' },
  { id:'p12', name:'Charm Me Anklet', cat:'Bracelets', price:279, mrp:559, rating:4.7, reviews:389, stock:11, tag:'TRENDING', color:'#C9B6FF', shape:'anklet', tone:'silver' },
  { id:'p13', name:'Sparkle Tennis Bracelet', cat:'Bracelets', price:449, mrp:999, rating:4.9, reviews:1034, stock:3, tag:'LOW STOCK', color:'#F4EFE6', shape:'tennis', tone:'silver' },
  { id:'p14', name:'Tiny Diamond Nose Pin', cat:'Nose Pins', price:149, mrp:299, rating:4.8, reviews:2340, stock:25, tag:'BESTSELLER', color:'#FAF7F1', shape:'nose', tone:'gold' },
  { id:'p15', name:'Moon & Stars Set (4pc)', cat:'Sets', price:499, mrp:1199, rating:4.9, reviews:892, stock:4, tag:'COMBO DEAL', color:'#0A0A0A', shape:'moonset', tone:'gold' },
  { id:'p16', name:'Coquette Ribbon Set', cat:'Sets', price:479, mrp:999, rating:4.8, reviews:567, stock:8, tag:'SET', color:'#FF2D6F', shape:'ribbonset', tone:'gold' },
];

const CATEGORIES = [
  { id:'earrings', name:'Earrings', count:128, color:'#FFE14A' },
  { id:'necklaces', name:'Necklaces', count:94, color:'#C9B6FF' },
  { id:'rings', name:'Rings', count:76, color:'#FF7A3D' },
  { id:'bracelets', name:'Bracelets', count:62, color:'#B8F2C9' },
  { id:'nose', name:'Nose Pins', count:38, color:'#FF2D6F' },
  { id:'sets', name:'Sets', count:45, color:'#0A0A0A' },
];

const REVIEWS = [
  { name:'Priya S.', avatar:'P', rating:5, text:"Legit obsessed 😭 the hoops came in cutest pink pouch, feels like a gift to myself every time I open it.", product:'Y2K Chrome Hoops', verified:true, color:'#FFE14A' },
  { name:'Ananya K.', avatar:'A', rating:5, text:"Quality for ₹299??? Shook. Wore them to a wedding and got asked where they're from like 5 times.", product:'Butter Heart Huggies', verified:true, color:'#C9B6FF' },
  { name:'Riya M.', avatar:'R', rating:5, text:"The packaging alone is chef's kiss. Feels premium for what I paid. Repeat customer for life ✨", product:'Pearl Baby Lariat', verified:true, color:'#FF7A3D' },
  { name:'Isha B.', avatar:'I', rating:4, text:"Super dainty and layers beautifully with my other chains. Not the sturdiest but for the price? 10/10.", product:'Layered Dainty Chain', verified:true, color:'#B8F2C9' },
];

// ───────────────────────────────────────────────────────────
// PRODUCT SVG — stylized jewelry illustrations on tinted bg
// ───────────────────────────────────────────────────────────
function ProductArt({ product, size=280 }) {
  const metal = product.tone === 'gold' ? '#E6B84D' : '#C8CCD4';
  const metalDeep = product.tone === 'gold' ? '#B8872C' : '#8F94A0';
  const bg = product.color;
  return (
    <svg viewBox="0 0 280 280" width={size} height={size} style={{display:'block', width:'100%', height:'100%'}} preserveAspectRatio="xMidYMid slice">
      <rect width="280" height="280" fill={bg}/>
      {/* soft vignette */}
      <radialGradient id={`v-${product.id}`} cx="50%" cy="40%" r="70%">
        <stop offset="0%" stopColor="rgba(255,255,255,0.4)"/>
        <stop offset="100%" stopColor="rgba(0,0,0,0.08)"/>
      </radialGradient>
      <rect width="280" height="280" fill={`url(#v-${product.id})`}/>

      {renderJewelryShape(product.shape, metal, metalDeep)}
    </svg>
  );
}

function renderJewelryShape(shape, m, md) {
  switch(shape) {
    case 'heart': return (
      <g>
        {[{x:105,y:130},{x:175,y:130}].map((p,i)=>(
          <g key={i} transform={`translate(${p.x} ${p.y})`}>
            <path d="M 0 -5 C -15 -25, -40 -10, 0 20 C 40 -10, 15 -25, 0 -5 Z" fill={m} stroke={md} strokeWidth="2"/>
            <circle cx="-8" cy="-12" r="3" fill="rgba(255,255,255,0.6)"/>
          </g>
        ))}
      </g>
    );
    case 'drop': return (
      <g>
        {[{x:110,y:110},{x:170,y:110}].map((p,i)=>(
          <g key={i} transform={`translate(${p.x} ${p.y})`}>
            <circle cx="0" cy="0" r="4" fill={m}/>
            <line x1="0" y1="4" x2="0" y2="30" stroke={m} strokeWidth="1.5"/>
            <ellipse cx="0" cy="50" rx="14" ry="18" fill="#F8F3E6" stroke={md} strokeWidth="1"/>
            <ellipse cx="-4" cy="44" rx="4" ry="6" fill="rgba(255,255,255,0.7)"/>
          </g>
        ))}
      </g>
    );
    case 'cherry': return (
      <g transform="translate(140 140)">
        <path d="M -30 -40 Q -10 -60 0 -40" stroke={m} strokeWidth="2.5" fill="none"/>
        <path d="M 30 -40 Q 10 -60 0 -40" stroke={m} strokeWidth="2.5" fill="none"/>
        <circle cx="-30" cy="-10" r="22" fill="#E11855"/>
        <circle cx="30" cy="-10" r="22" fill="#E11855"/>
        <circle cx="-36" cy="-16" r="6" fill="rgba(255,255,255,0.4)"/>
        <circle cx="24" cy="-16" r="6" fill="rgba(255,255,255,0.4)"/>
        <path d="M -15 -45 Q -5 -50 5 -45 Q 0 -35 -10 -38 Z" fill="#0E8A5F"/>
      </g>
    );
    case 'hoop': return (
      <g>
        {[{x:110,y:140},{x:170,y:140}].map((p,i)=>(
          <g key={i} transform={`translate(${p.x} ${p.y})`}>
            <circle cx="0" cy="0" r="32" fill="none" stroke={m} strokeWidth="7"/>
            <circle cx="0" cy="0" r="32" fill="none" stroke={md} strokeWidth="1.5" opacity="0.4"/>
            <circle cx="-22" cy="-22" r="4" fill="rgba(255,255,255,0.7)"/>
          </g>
        ))}
      </g>
    );
    case 'star': return (
      <g>
        <path d="M 40 170 Q 140 140, 240 170" stroke={m} strokeWidth="2" fill="none"/>
        {[0,1,2,3,4].map(i=>{
          const x = 70 + i*35;
          const y = 165 + Math.sin(i)*8;
          return (
            <g key={i} transform={`translate(${x} ${y})`}>
              <path d="M 0 -10 L 3 -3 L 10 -3 L 4 2 L 6 9 L 0 5 L -6 9 L -4 2 L -10 -3 L -3 -3 Z" fill={m} stroke={md} strokeWidth="0.5"/>
            </g>
          );
        })}
      </g>
    );
    case 'chain': return (
      <g>
        <path d="M 30 130 Q 140 100, 250 130 L 250 135 Q 140 105, 30 135 Z" fill={m}/>
        <path d="M 50 165 Q 140 150, 230 165 L 230 169 Q 140 154, 50 169 Z" fill={m} opacity="0.85"/>
        <circle cx="140" cy="175" r="8" fill={m}/>
        <path d="M 140 175 L 140 195 M 135 190 L 145 190" stroke={md} strokeWidth="2"/>
      </g>
    );
    case 'lariat': return (
      <g>
        <path d="M 50 110 Q 140 160, 230 110" stroke={m} strokeWidth="2" fill="none"/>
        <circle cx="140" cy="155" r="6" fill="#FAF7F1" stroke={md} strokeWidth="1"/>
        <line x1="140" y1="161" x2="140" y2="185" stroke={m} strokeWidth="1.2"/>
        <circle cx="140" cy="192" r="8" fill="#FAF7F1" stroke={md} strokeWidth="1"/>
      </g>
    );
    case 'rings': return (
      <g>
        {[{x:85,y:140,r:35,c:m},{x:140,y:140,r:35,c:'#FF2D6F'},{x:195,y:140,r:35,c:m}].map((p,i)=>(
          <g key={i}>
            <circle cx={p.x} cy={p.y} r={p.r} fill="none" stroke={p.c} strokeWidth="10"/>
            {p.c !== m ? <circle cx={p.x} cy={p.y-25} r="5" fill={p.c}/> : null}
          </g>
        ))}
      </g>
    );
    case 'signet': return (
      <g transform="translate(140 140)">
        <circle cx="0" cy="0" r="50" fill="none" stroke={m} strokeWidth="8"/>
        <ellipse cx="0" cy="-30" rx="30" ry="22" fill={m}/>
        <ellipse cx="0" cy="-30" rx="22" ry="15" fill="#3B82F6"/>
        <circle cx="0" cy="-30" r="7" fill="#0A0A0A"/>
        <circle cx="0" cy="-30" r="3" fill="#FFF"/>
      </g>
    );
    case 'daisy': return (
      <g transform="translate(140 140)">
        <circle cx="0" cy="0" r="50" fill="none" stroke={m} strokeWidth="6"/>
        {[0,1,2,3,4,5,6,7].map(i=>{
          const a = (i*Math.PI*2)/8;
          return <ellipse key={i} cx={Math.cos(a)*14} cy={-30+Math.sin(a)*14} rx="5" ry="9" fill="#FFF" transform={`rotate(${i*45} ${Math.cos(a)*14} ${-30+Math.sin(a)*14})`}/>;
        })}
        <circle cx="0" cy="-30" r="6" fill="#FFE14A"/>
      </g>
    );
    case 'paperclip': return (
      <g>
        <rect x="50" y="130" width="30" height="14" rx="7" fill="none" stroke={m} strokeWidth="4"/>
        <rect x="78" y="130" width="40" height="14" rx="7" fill="none" stroke={m} strokeWidth="4"/>
        <rect x="116" y="130" width="30" height="14" rx="7" fill="none" stroke={m} strokeWidth="4"/>
        <rect x="144" y="130" width="40" height="14" rx="7" fill="none" stroke={m} strokeWidth="4"/>
        <rect x="182" y="130" width="30" height="14" rx="7" fill="none" stroke={m} strokeWidth="4"/>
        <rect x="210" y="130" width="20" height="14" rx="7" fill="none" stroke={m} strokeWidth="4"/>
      </g>
    );
    case 'anklet': return (
      <g>
        <path d="M 50 140 Q 140 110, 230 140" stroke={m} strokeWidth="3" fill="none" strokeDasharray="1 4"/>
        <circle cx="90" cy="132" r="5" fill="#FF2D6F"/>
        <path d="M 140 120 L 143 128 L 151 128 L 144 133 L 147 141 L 140 136 L 133 141 L 136 133 L 129 128 L 137 128 Z" fill={m}/>
        <circle cx="190" cy="132" r="5" fill="#C9B6FF"/>
      </g>
    );
    case 'tennis': return (
      <g>
        <path d="M 30 140 L 250 140" stroke={m} strokeWidth="2"/>
        {[...Array(11)].map((_,i)=>(
          <g key={i} transform={`translate(${40+i*20} 140)`}>
            <rect x="-6" y="-6" width="12" height="12" fill="#FFF" stroke={m} strokeWidth="1" transform="rotate(45)"/>
          </g>
        ))}
      </g>
    );
    case 'nose': return (
      <g transform="translate(140 140)">
        <circle cx="0" cy="0" r="4" fill="#FFF" stroke={m} strokeWidth="1"/>
        <path d="M 0 0 Q -30 10, -25 40 Q -20 55, 0 55 Q 20 55, 25 40 Q 30 10, 0 0" fill="none" stroke={m} strokeWidth="1.5"/>
        <circle cx="0" cy="0" r="2" fill="#FF2D6F"/>
      </g>
    );
    case 'moonset': return (
      <g>
        <g transform="translate(80 90)"><path d="M 20 0 A 20 20 0 1 1 20 40 A 15 15 0 0 0 20 0 Z" fill={m}/></g>
        <g transform="translate(180 90)"><path d="M 0 -12 L 3 -3 L 12 -3 L 5 3 L 7 12 L 0 7 L -7 12 L -5 3 L -12 -3 L -3 -3 Z" fill={m} transform="scale(1.5)"/></g>
        <g transform="translate(90 200)"><path d="M 0 -8 L 2 -2 L 8 -2 L 3 2 L 5 8 L 0 5 L -5 8 L -3 2 L -8 -2 L -2 -2 Z" fill={m} transform="scale(1.2)"/></g>
        <g transform="translate(200 200)"><circle cx="0" cy="0" r="14" fill="#FFE14A" stroke={m} strokeWidth="2"/></g>
      </g>
    );
    case 'ribbonset': return (
      <g transform="translate(140 140)">
        <path d="M -50 -10 Q -70 -30, -40 -20 Q -30 -10, 0 0 Q 30 -10, 40 -20 Q 70 -30, 50 -10 Q 30 10, 0 10 Q -30 10, -50 -10 Z" fill="#FFF" stroke={m} strokeWidth="2"/>
        <path d="M -10 0 L 10 0 L 15 25 L -15 25 Z" fill="#FFF" stroke={m} strokeWidth="2"/>
        <circle cx="0" cy="0" r="6" fill={m}/>
      </g>
    );
    default: return <circle cx="140" cy="140" r="40" fill={m}/>;
  }
}

// ───────────────────────────────────────────────────────────
// LIFESTYLE / MODEL PLACEHOLDERS — abstract "girl wearing jewelry" bg
// ───────────────────────────────────────────────────────────
function LifestyleArt({ variant=1, width=600, height=800, overlay=null }) {
  const palettes = [
    { sky:'#F4EFE6', skin:'#E8B499', hair:'#2A1810', shirt:'#FF2D6F' },
    { sky:'#FFE14A', skin:'#D49B7A', hair:'#0A0A0A', shirt:'#0A0A0A' },
    { sky:'#C9B6FF', skin:'#E8B499', hair:'#4A2818', shirt:'#FFF' },
    { sky:'#B8F2C9', skin:'#F0C8A8', hair:'#1A0F08', shirt:'#0A0A0A' },
    { sky:'#FF7A3D', skin:'#E8B499', hair:'#2A1810', shirt:'#FFF' },
  ];
  const p = palettes[(variant-1) % palettes.length];
  return (
    <svg viewBox="0 0 600 800" width={width} height={height} style={{display:'block', width:'100%', height:'100%'}} preserveAspectRatio="xMidYMid slice">
      <rect width="600" height="800" fill={p.sky}/>
      {/* Soft gradient overlay */}
      <defs>
        <linearGradient id={`lg-${variant}`} x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor="rgba(255,255,255,0.2)"/>
          <stop offset="100%" stopColor="rgba(0,0,0,0.15)"/>
        </linearGradient>
      </defs>
      <rect width="600" height="800" fill={`url(#lg-${variant})`}/>
      {/* Abstract figure — head + neck + shoulders */}
      <g>
        {/* Hair back */}
        <ellipse cx="300" cy="320" rx="150" ry="180" fill={p.hair}/>
        {/* Neck */}
        <rect x="260" y="380" width="80" height="100" fill={p.skin}/>
        {/* Shoulders/shirt */}
        <path d="M 100 480 Q 300 450, 500 480 L 500 800 L 100 800 Z" fill={p.shirt}/>
        {/* Face */}
        <ellipse cx="300" cy="340" rx="110" ry="140" fill={p.skin}/>
        {/* Hair front */}
        <path d="M 190 250 Q 250 190, 300 210 Q 350 190, 410 250 Q 420 300, 400 280 Q 380 250, 350 260 Q 310 240, 300 260 Q 290 240, 250 260 Q 220 250, 200 280 Q 180 300, 190 250 Z" fill={p.hair}/>
        {/* Jewelry hint — earring */}
        <circle cx="205" cy="370" r="8" fill="#E6B84D"/>
        <circle cx="395" cy="370" r="8" fill="#E6B84D"/>
        {/* Necklace */}
        <path d="M 220 500 Q 300 540, 380 500" stroke="#E6B84D" strokeWidth="3" fill="none"/>
        <circle cx="300" cy="535" r="6" fill="#FF2D6F"/>
      </g>
      {/* Soft grain overlay */}
      <rect width="600" height="800" fill="url(#noise)" opacity="0.05"/>
      {overlay}
    </svg>
  );
}

// ───────────────────────────────────────────────────────────
// UI PRIMITIVES
// ───────────────────────────────────────────────────────────
function Stars({ value=5, size=12, color='#FFB800' }) {
  return (
    <div style={{display:'inline-flex', gap:1}}>
      {[1,2,3,4,5].map(i=>(
        <svg key={i} width={size} height={size} viewBox="0 0 16 16">
          <path d="M 8 1 L 10 6 L 15 6.5 L 11 10 L 12.5 15 L 8 12 L 3.5 15 L 5 10 L 1 6.5 L 6 6 Z"
            fill={i <= Math.round(value) ? color : '#E6E1D6'}/>
        </svg>
      ))}
    </div>
  );
}

function Price({ price, mrp, size='md' }) {
  const off = mrp ? Math.round(((mrp - price) / mrp) * 100) : 0;
  const fs = size === 'lg' ? 22 : size === 'sm' ? 13 : 16;
  return (
    <div style={{display:'flex', alignItems:'baseline', gap:6, flexWrap:'wrap'}}>
      <span style={{fontSize:fs, fontWeight:900, fontFamily:'var(--font-display)', color:'var(--ink)'}}>₹{price}</span>
      {mrp ? <span style={{fontSize:fs*0.75, color:'var(--muted)', textDecoration:'line-through'}}>₹{mrp}</span> : null}
      {mrp ? <span style={{fontSize:fs*0.7, fontWeight:800, color:'var(--hot)'}}>{off}% OFF</span> : null}
    </div>
  );
}

function Pill({ children, bg='var(--ink)', color='#fff', size='md' }) {
  const py = size === 'sm' ? 4 : 6;
  const px = size === 'sm' ? 8 : 12;
  const fs = size === 'sm' ? 10 : 11;
  return (
    <span style={{
      background: bg, color, padding:`${py}px ${px}px`, borderRadius:999,
      fontSize:fs, fontWeight:800, textTransform:'uppercase', letterSpacing:'0.06em',
      display:'inline-flex', alignItems:'center', gap:4,
    }}>{children}</span>
  );
}

function CountdownTimer({ seconds=7845, compact=false }) {
  const [t, setT] = React.useState(seconds);
  React.useEffect(()=>{
    const id = setInterval(()=> setT(x => x > 0 ? x - 1 : 0), 1000);
    return ()=> clearInterval(id);
  },[]);
  const h = Math.floor(t/3600);
  const m = Math.floor((t%3600)/60);
  const s = t%60;
  const pad = n => String(n).padStart(2,'0');
  const box = (label, val) => (
    <div style={{display:'flex', flexDirection:'column', alignItems:'center', gap:2}}>
      <div style={{
        background: 'var(--ink)', color:'var(--butter)',
        fontFamily:'var(--font-mono)', fontWeight:700,
        padding: compact ? '4px 8px' : '10px 12px',
        minWidth: compact ? 32 : 48,
        borderRadius: compact ? 6 : 10,
        fontSize: compact ? 14 : 22,
        textAlign:'center',
      }}>{pad(val)}</div>
      {!compact && <div style={{fontSize:9, color:'var(--muted)', fontWeight:700, letterSpacing:'0.1em'}}>{label}</div>}
    </div>
  );
  return (
    <div style={{display:'flex', gap: compact ? 4 : 8, alignItems:'center'}}>
      {box('HRS', h)}
      {!compact && <div style={{fontWeight:900, color:'var(--ink)'}}>:</div>}
      {box('MIN', m)}
      {!compact && <div style={{fontWeight:900, color:'var(--ink)'}}>:</div>}
      {box('SEC', s)}
    </div>
  );
}

// Header
function SiteHeader({ active='Home' }) {
  const links = ['Home', 'Shop', 'Bestsellers', 'Under ₹299', 'Sets', 'Lookbook', 'About'];
  return (
    <header style={{borderBottom:'1px solid var(--line)', background:'var(--white)', position:'sticky', top:0, zIndex:40}}>
      {/* Announcement strip */}
      <div style={{background:'var(--ink)', color:'#fff', textAlign:'center', padding:'8px 0', fontSize:12, fontWeight:700, overflow:'hidden'}}>
        <div style={{display:'flex', gap:40, whiteSpace:'nowrap', animation:'marquee 22s linear infinite', width:'max-content'}}>
          {Array.from({length:6}).flatMap((_,i)=>[
            <span key={`a${i}`}>⚡ FLAT 50% OFF SITEWIDE — CODE: <span style={{color:'var(--butter)'}}>GENZ50</span></span>,
            <span key={`b${i}`}>🚚 FREE SHIPPING OVER ₹499</span>,
            <span key={`c${i}`}>💝 BUY 2 GET 1 FREE ON ALL SETS</span>,
            <span key={`d${i}`}>✨ COD AVAILABLE</span>,
          ])}
        </div>
      </div>
      {/* Main */}
      <div style={{display:'flex', alignItems:'center', justifyContent:'space-between', padding:'16px 40px', maxWidth:1440, margin:'0 auto'}}>
        <a style={{display:'flex', alignItems:'center', gap:8}}>
          <div style={{width:36, height:36, borderRadius:999, background:'var(--ink)', color:'var(--butter)', display:'grid', placeItems:'center', fontFamily:'var(--font-display)', fontWeight:900, fontSize:18, fontStyle:'italic'}}>g</div>
          <span style={{fontFamily:'var(--font-display)', fontWeight:900, fontSize:22, letterSpacing:'-0.03em'}}>genzee<span style={{color:'var(--hot)'}}>.</span></span>
        </a>
        <nav style={{display:'flex', gap:28}}>
          {links.map(l=>(
            <a key={l} style={{
              fontSize:13, fontWeight:700,
              color: l===active ? 'var(--hot)' : 'var(--ink)',
              textTransform:'uppercase', letterSpacing:'0.05em',
              position:'relative',
            }}>
              {l}
              {l==='Under ₹299' && <span style={{position:'absolute', top:-8, right:-18, background:'var(--butter)', color:'var(--ink)', fontSize:8, fontWeight:900, padding:'1px 5px', borderRadius:4, letterSpacing:0}}>HOT</span>}
            </a>
          ))}
        </nav>
        <div style={{display:'flex', alignItems:'center', gap:18}}>
          <button aria-label="search"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2"><circle cx="11" cy="11" r="7"/><path d="M 16 16 L 21 21"/></svg></button>
          <button aria-label="account"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2"><circle cx="12" cy="8" r="4"/><path d="M 4 21 Q 12 15 20 21"/></svg></button>
          <button aria-label="cart" style={{position:'relative'}}>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2"><path d="M 5 7 L 19 7 L 17.5 18 Q 17 20 15 20 L 9 20 Q 7 20 6.5 18 Z"/><path d="M 9 7 Q 9 3 12 3 Q 15 3 15 7"/></svg>
            <span style={{position:'absolute', top:-6, right:-8, background:'var(--hot)', color:'#fff', fontSize:10, fontWeight:900, padding:'2px 6px', borderRadius:999, minWidth:18, textAlign:'center'}}>3</span>
          </button>
        </div>
      </div>
    </header>
  );
}

// Footer
function SiteFooter() {
  return (
    <footer style={{background:'var(--ink)', color:'#fff', padding:'60px 40px 24px'}}>
      <div style={{maxWidth:1440, margin:'0 auto', display:'grid', gridTemplateColumns:'2fr 1fr 1fr 1fr 1.5fr', gap:48}}>
        <div>
          <div style={{fontFamily:'var(--font-display)', fontWeight:900, fontSize:32, letterSpacing:'-0.03em'}}>genzee<span style={{color:'var(--hot)'}}>.</span></div>
          <p style={{fontSize:13, color:'rgba(255,255,255,0.6)', lineHeight:1.6, marginTop:12, maxWidth:320}}>
            Jewelry that slaps. Everything under ₹499. Shipped in 24hrs, wrapped in pink, designed to feel like a treat.
          </p>
          <div style={{display:'flex', gap:10, marginTop:20}}>
            {['IG','TT','YT','PN'].map(s=>(
              <div key={s} style={{width:34, height:34, borderRadius:999, background:'rgba(255,255,255,0.1)', display:'grid', placeItems:'center', fontSize:10, fontWeight:800}}>{s}</div>
            ))}
          </div>
        </div>
        {[
          { h:'Shop', items:['New Drops','Bestsellers','Under ₹299','Sets & Combos','Sale'] },
          { h:'Help', items:['Track Order','Shipping','Returns','Size Guide','Contact'] },
          { h:'Company', items:['About','Careers','Sustainability','Press','Wholesale'] },
        ].map(col=>(
          <div key={col.h}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--butter)', marginBottom:16}}>{col.h.toUpperCase()}</div>
            {col.items.map(it=><div key={it} style={{fontSize:13, color:'rgba(255,255,255,0.7)', marginBottom:10, cursor:'pointer'}}>{it}</div>)}
          </div>
        ))}
        <div>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--butter)', marginBottom:16}}>GET 15% OFF</div>
          <p style={{fontSize:13, color:'rgba(255,255,255,0.7)', marginBottom:14}}>Sign up. First order gets a freebie 💝</p>
          <div style={{display:'flex', background:'rgba(255,255,255,0.08)', borderRadius:999, padding:4}}>
            <input placeholder="your@email.com" style={{flex:1, background:'transparent', border:0, color:'#fff', padding:'10px 14px', fontSize:13, outline:'none'}}/>
            <button style={{background:'var(--hot)', color:'#fff', borderRadius:999, padding:'10px 18px', fontSize:12, fontWeight:800}}>JOIN</button>
          </div>
        </div>
      </div>
      <div style={{maxWidth:1440, margin:'48px auto 0', paddingTop:24, borderTop:'1px solid rgba(255,255,255,0.1)', display:'flex', justifyContent:'space-between', fontSize:11, color:'rgba(255,255,255,0.5)'}}>
        <div>© 2026 Genzee Jewels · Made with ♥ in Jaipur</div>
        <div style={{display:'flex', gap:20}}>
          <span>Privacy</span><span>Terms</span><span>Cookies</span>
        </div>
      </div>
    </footer>
  );
}

// Product card
function ProductCard({ product, onQuickAdd, variant='default' }) {
  const [hover, setHover] = React.useState(false);
  const off = Math.round(((product.mrp - product.price) / product.mrp) * 100);
  return (
    <div
      onMouseEnter={()=>setHover(true)}
      onMouseLeave={()=>setHover(false)}
      style={{position:'relative', background:'#fff', borderRadius:'var(--r-md)', overflow:'hidden', cursor:'pointer', transition:'transform .2s'}}>
      <div style={{position:'relative', aspectRatio:'1/1', overflow:'hidden'}}>
        <ProductArt product={product}/>
        {/* Tag */}
        {product.tag && (
          <div style={{position:'absolute', top:10, left:10}}>
            <Pill bg={product.tag==='LOW STOCK' || product.tag==='🔥 TRENDING' ? 'var(--hot)' : product.tag==='NEW' ? 'var(--ink)' : product.tag==='COMBO DEAL' ? 'var(--ink)' : 'var(--butter)'} color={product.tag==='BESTSELLER' || product.tag==='SET' ? 'var(--ink)' : '#fff'} size="sm">{product.tag}</Pill>
          </div>
        )}
        {/* Discount chip */}
        <div style={{position:'absolute', top:10, right:10, background:'var(--ink)', color:'var(--butter)', padding:'4px 8px', borderRadius:6, fontSize:11, fontWeight:900, fontFamily:'var(--font-display)'}}>
          -{off}%
        </div>
        {/* Hover quick-add */}
        <div style={{
          position:'absolute', left:10, right:10, bottom:10,
          transform: hover ? 'translateY(0)' : 'translateY(calc(100% + 10px))',
          transition:'transform .25s cubic-bezier(.2,.9,.3,1.1)',
        }}>
          <button
            onClick={(e)=>{ e.stopPropagation(); onQuickAdd && onQuickAdd(product); }}
            style={{
              width:'100%', padding:'12px', borderRadius:999,
              background:'var(--ink)', color:'#fff',
              fontSize:12, fontWeight:800, letterSpacing:'0.05em', textTransform:'uppercase',
              display:'flex', alignItems:'center', justifyContent:'center', gap:6,
            }}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M 12 5 L 12 19 M 5 12 L 19 12"/></svg>
            Quick Add · ₹{product.price}
          </button>
        </div>
        {/* Wishlist */}
        <button style={{position:'absolute', bottom:10, right:10, background:'#fff', width:32, height:32, borderRadius:999, display:'grid', placeItems:'center', opacity: hover ? 0 : 1, transition:'opacity .2s', boxShadow:'0 2px 6px rgba(0,0,0,0.1)'}}>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M 12 21 C 6 17 2 13 2 8 A 5 5 0 0 1 12 6 A 5 5 0 0 1 22 8 C 22 13 18 17 12 21 Z"/></svg>
        </button>
      </div>
      <div style={{padding:'14px 14px 16px'}}>
        <div style={{fontSize:9, color:'var(--muted)', fontWeight:800, letterSpacing:'0.08em', textTransform:'uppercase', marginBottom:4}}>{product.cat}</div>
        <div style={{fontSize:13, fontWeight:700, color:'var(--ink)', marginBottom:6, lineHeight:1.3}}>{product.name}</div>
        <div style={{display:'flex', alignItems:'center', gap:6, marginBottom:8}}>
          <Stars value={product.rating} size={10}/>
          <span style={{fontSize:10, color:'var(--muted)', fontWeight:600}}>({product.reviews})</span>
        </div>
        <Price price={product.price} mrp={product.mrp} size="sm"/>
        {product.stock <= 5 && (
          <div style={{marginTop:8, display:'flex', alignItems:'center', gap:5, fontSize:10, fontWeight:700, color:'var(--hot)'}}>
            <span className="pulse-dot" style={{width:6, height:6, borderRadius:999, background:'var(--hot)'}}/>
            Only {product.stock} left!
          </div>
        )}
      </div>
    </div>
  );
}

// Live purchase notification
function LivePurchaseToast({ bottom=20 }) {
  const [idx, setIdx] = React.useState(0);
  const [show, setShow] = React.useState(true);
  const notifications = [
    { name:'Priya from Mumbai', product:'Butter Heart Huggies', time:'2 min ago', color:'#FFE14A' },
    { name:'Ananya from Delhi', product:'Cherry Bomb Studs', time:'5 min ago', color:'#FF2D6F' },
    { name:'Riya from Bangalore', product:'Pearl Baby Lariat', time:'8 min ago', color:'#F4EFE6' },
    { name:'Ishita from Pune', product:'Evil Eye Signet', time:'12 min ago', color:'#3B82F6' },
  ];
  React.useEffect(()=>{
    const id = setInterval(()=>{
      setShow(false);
      setTimeout(()=>{
        setIdx(i => (i+1) % notifications.length);
        setShow(true);
      }, 400);
    }, 5000);
    return ()=> clearInterval(id);
  },[]);
  const n = notifications[idx];
  return (
    <div style={{
      position:'absolute', left:20, bottom,
      background:'#fff', borderRadius:14, padding:'12px 16px 12px 12px',
      display:'flex', alignItems:'center', gap:12, maxWidth:300,
      boxShadow:'0 8px 24px rgba(0,0,0,0.12)', border:'1px solid var(--line)',
      opacity: show ? 1 : 0, transform: show ? 'translateY(0)' : 'translateY(20px)',
      transition:'all .35s cubic-bezier(.2,.9,.3,1.1)', zIndex:30,
    }}>
      <div style={{width:40, height:40, borderRadius:10, background:n.color, flexShrink:0, display:'grid', placeItems:'center'}}>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="var(--ink)"><path d="M 12 2 L 14 8 L 20 8.5 L 15 12 L 17 18 L 12 15 L 7 18 L 9 12 L 4 8.5 L 10 8 Z"/></svg>
      </div>
      <div style={{flex:1, minWidth:0}}>
        <div style={{fontSize:11, fontWeight:800, color:'var(--ink)'}}>{n.name}</div>
        <div style={{fontSize:11, color:'var(--muted)', marginTop:2, overflow:'hidden', textOverflow:'ellipsis', whiteSpace:'nowrap'}}>bought <b style={{color:'var(--ink)'}}>{n.product}</b></div>
        <div style={{fontSize:9, color:'var(--muted)', marginTop:2, fontWeight:600}}>✓ {n.time}</div>
      </div>
      <button style={{fontSize:16, color:'var(--muted)'}} onClick={()=>setShow(false)}>×</button>
    </div>
  );
}

Object.assign(window, {
  PRODUCTS, CATEGORIES, REVIEWS,
  ProductArt, LifestyleArt,
  Stars, Price, Pill, CountdownTimer,
  SiteHeader, SiteFooter, ProductCard, LivePurchaseToast,
});
