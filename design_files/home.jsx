// home.jsx — Genzee Jewels homepage

function HomePage() {
  const [cartCount, setCartCount] = React.useState(3);
  const bestsellers = PRODUCTS.filter(p=>['p1','p3','p6','p8','p14','p11'].includes(p.id));
  const newDrops = PRODUCTS.slice(0,8);
  const under299 = PRODUCTS.filter(p=>p.price<=299);

  return (
    <div style={{background:'var(--paper)', fontFamily:'var(--font-body)', minHeight:'100vh', position:'relative'}}>
      <SiteHeader active="Home"/>

      {/* HERO — split grid: lifestyle + category tiles for instant shopping */}
      <section style={{maxWidth:1440, margin:'0 auto', padding:'24px 40px 0', display:'grid', gridTemplateColumns:'1.4fr 1fr', gap:16}}>
        {/* Big editorial tile */}
        <div style={{position:'relative', borderRadius:'var(--r-lg)', overflow:'hidden', aspectRatio:'5/4', background:'linear-gradient(135deg, #FF2D6F 0%, #FF7A3D 100%)'}}>
          <div style={{position:'absolute', inset:0}}>
            <LifestyleArt variant={1}/>
          </div>
          <div style={{position:'absolute', inset:0, background:'linear-gradient(160deg, rgba(255,45,111,0.1) 0%, rgba(0,0,0,0.5) 100%)'}}/>
          {/* Corner pill */}
          <div style={{position:'absolute', top:20, left:20, display:'flex', gap:8}}>
            <Pill bg="#fff" color="var(--ink)"><span className="pulse-dot" style={{width:6,height:6,borderRadius:999,background:'var(--hot)',display:'inline-block', marginRight:4}}/>LIVE DROP</Pill>
            <Pill bg="var(--butter)" color="var(--ink)">SUMMER '26</Pill>
          </div>
          {/* Big copy */}
          <div style={{position:'absolute', left:32, right:32, bottom:32, color:'#fff'}}>
            <div style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontSize:16, opacity:0.9, marginBottom:8}}>Our new collection, just dropped —</div>
            <h1 style={{fontFamily:'var(--font-display)', fontSize:84, fontWeight:900, letterSpacing:'-0.04em', lineHeight:0.9, margin:0}}>
              Slay<br/>on<span style={{color:'var(--butter)'}}>a</span><br/>budget.
            </h1>
            <div style={{display:'flex', gap:12, marginTop:24, alignItems:'center'}}>
              <button style={{background:'#fff', color:'var(--ink)', padding:'16px 28px', borderRadius:999, fontWeight:900, fontSize:13, letterSpacing:'0.04em', textTransform:'uppercase', display:'flex', alignItems:'center', gap:8}}>
                Shop Summer Drop
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3"><path d="M 5 12 L 19 12 M 13 6 L 19 12 L 13 18"/></svg>
              </button>
              <div style={{color:'#fff', fontSize:13, fontWeight:700}}>
                <div style={{opacity:0.7, fontSize:10, letterSpacing:'0.1em'}}>EVERYTHING</div>
                <div style={{fontFamily:'var(--font-display)', fontSize:22, fontWeight:900}}>Under ₹499</div>
              </div>
            </div>
          </div>
        </div>

        {/* 2×2 mini-grid: deal + category tiles */}
        <div style={{display:'grid', gridTemplateRows:'1fr 1fr', gap:16}}>
          {/* Deal-of-the-day */}
          <div style={{background:'var(--ink)', color:'#fff', borderRadius:'var(--r-lg)', padding:24, position:'relative', overflow:'hidden'}}>
            <div style={{position:'absolute', top:-40, right:-40, width:180, height:180, borderRadius:999, background:'var(--hot)', opacity:0.3}}/>
            <div style={{position:'relative', display:'flex', flexDirection:'column', height:'100%'}}>
              <div style={{display:'flex', alignItems:'center', gap:8, marginBottom:12}}>
                <span className="pulse-dot" style={{width:8, height:8, borderRadius:999, background:'var(--hot)'}}/>
                <span style={{fontSize:10, fontWeight:800, letterSpacing:'0.1em'}}>DEAL OF THE DAY</span>
              </div>
              <div style={{fontFamily:'var(--font-display)', fontSize:28, fontWeight:900, lineHeight:1, letterSpacing:'-0.02em'}}>Flat <span style={{color:'var(--butter)'}}>70% off</span><br/>on Sets</div>
              <div style={{flex:1}}/>
              <div style={{marginTop:16}}>
                <div style={{fontSize:10, letterSpacing:'0.1em', opacity:0.6, marginBottom:6}}>ENDS IN</div>
                <CountdownTimer seconds={7845} compact/>
              </div>
            </div>
          </div>
          {/* Featured small tiles */}
          <div style={{display:'grid', gridTemplateColumns:'1fr 1fr', gap:16}}>
            <div style={{background:'var(--butter)', borderRadius:'var(--r-lg)', padding:18, position:'relative', overflow:'hidden'}}>
              <div style={{position:'absolute', bottom:-20, right:-20, width:100, height:100}}>
                <ProductArt product={PRODUCTS[0]}/>
              </div>
              <div style={{fontSize:10, fontWeight:800, letterSpacing:'0.1em', color:'var(--ink)'}}>NEW IN</div>
              <div style={{fontFamily:'var(--font-display)', fontSize:22, fontWeight:900, lineHeight:1, color:'var(--ink)', marginTop:4}}>Earrings<br/>dump.</div>
              <div style={{fontSize:11, fontWeight:700, color:'var(--ink)', marginTop:44}}>Shop 40+ styles →</div>
            </div>
            <div style={{background:'var(--lilac)', borderRadius:'var(--r-lg)', padding:18, position:'relative', overflow:'hidden'}}>
              <div style={{position:'absolute', bottom:-15, right:-15, width:90, height:90}}>
                <ProductArt product={PRODUCTS[5]}/>
              </div>
              <div style={{fontSize:10, fontWeight:800, letterSpacing:'0.1em', color:'var(--ink)'}}>TRENDING</div>
              <div style={{fontFamily:'var(--font-display)', fontSize:22, fontWeight:900, lineHeight:1, color:'var(--ink)', marginTop:4}}>Dainty<br/>chains.</div>
              <div style={{fontSize:11, fontWeight:700, color:'var(--ink)', marginTop:44}}>From ₹299 →</div>
            </div>
          </div>
        </div>
      </section>

      {/* TRUST STRIP */}
      <section style={{maxWidth:1440, margin:'32px auto 0', padding:'0 40px'}}>
        <div style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-lg)', padding:'20px 28px', display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:24}}>
          {[
            { icon:'🚚', t:'Free Shipping', s:'on orders over ₹499' },
            { icon:'💝', t:'Pink Packaging', s:'giftable by default' },
            { icon:'↩️', t:'7-day Returns', s:'no-questions-asked' },
            { icon:'💸', t:'COD Available', s:'pay when it arrives' },
          ].map(it=>(
            <div key={it.t} style={{display:'flex', alignItems:'center', gap:12}}>
              <div style={{fontSize:24}}>{it.icon}</div>
              <div>
                <div style={{fontSize:13, fontWeight:800}}>{it.t}</div>
                <div style={{fontSize:11, color:'var(--muted)'}}>{it.s}</div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* CATEGORIES */}
      <section style={{maxWidth:1440, margin:'64px auto 0', padding:'0 40px'}}>
        <div style={{display:'flex', alignItems:'end', justifyContent:'space-between', marginBottom:24}}>
          <div>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>SHOP BY CATEGORY</div>
            <h2 style={{fontFamily:'var(--font-display)', fontSize:44, fontWeight:900, letterSpacing:'-0.03em', margin:0}}>Pick your<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> poison </span>→</h2>
          </div>
          <a style={{fontSize:12, fontWeight:800, letterSpacing:'0.05em', textTransform:'uppercase'}}>View all →</a>
        </div>
        <div style={{display:'grid', gridTemplateColumns:'repeat(6, 1fr)', gap:14}}>
          {CATEGORIES.map(c=>(
            <div key={c.id} style={{borderRadius:'var(--r-lg)', background:c.color, aspectRatio:'3/4', padding:16, position:'relative', overflow:'hidden', cursor:'pointer'}}>
              <div style={{position:'absolute', inset:0, opacity:0.4}}>
                <ProductArt product={{...PRODUCTS.find(p=>p.cat.toLowerCase().includes(c.name.toLowerCase().slice(0,4)) || PRODUCTS[0]), color:c.color}}/>
              </div>
              <div style={{position:'relative', display:'flex', flexDirection:'column', justifyContent:'space-between', height:'100%'}}>
                <div style={{fontSize:9, fontWeight:800, letterSpacing:'0.1em', color: c.id==='sets' ? '#fff' : 'var(--ink)'}}>{c.count} ITEMS</div>
                <div>
                  <div style={{fontFamily:'var(--font-display)', fontWeight:900, fontSize:20, lineHeight:1, color: c.id==='sets' ? '#fff' : 'var(--ink)', letterSpacing:'-0.02em'}}>{c.name}</div>
                  <div style={{fontSize:10, fontWeight:700, color: c.id==='sets' ? 'rgba(255,255,255,0.7)' : 'var(--ink)', opacity: c.id==='sets' ? 1 : 0.6, marginTop:4}}>From ₹{c.id==='sets'?299:c.id==='nose'?149:179}</div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* UNDER ₹299 STRIP — impulse focus */}
      <section style={{marginTop:64, background:'var(--ink)', color:'#fff', padding:'48px 0 56px', position:'relative', overflow:'hidden'}}>
        <div style={{position:'absolute', inset:0, background:'radial-gradient(circle at 20% 50%, rgba(255,45,111,0.25) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(255,225,74,0.2) 0%, transparent 50%)'}}/>
        <div style={{maxWidth:1440, margin:'0 auto', padding:'0 40px', position:'relative'}}>
          <div style={{display:'flex', alignItems:'end', justifyContent:'space-between', marginBottom:24}}>
            <div>
              <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--butter)', marginBottom:6}}>⚡ IMPULSE ZONE</div>
              <h2 style={{fontFamily:'var(--font-display)', fontSize:64, fontWeight:900, letterSpacing:'-0.04em', margin:0, lineHeight:0.95}}>Under ₹299.<br/><span style={{color:'var(--butter)', fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}>no guilt.</span></h2>
            </div>
            <div style={{textAlign:'right'}}>
              <div style={{fontSize:12, opacity:0.6, marginBottom:6}}>DEAL ENDS IN</div>
              <CountdownTimer seconds={14400}/>
            </div>
          </div>
          <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:16}}>
            {under299.slice(0,4).map(p=>(
              <div key={p.id} style={{background:'#fff', borderRadius:'var(--r-md)', overflow:'hidden'}}>
                <ProductCard product={p}/>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* BESTSELLERS */}
      <section style={{maxWidth:1440, margin:'64px auto 0', padding:'0 40px'}}>
        <div style={{display:'flex', alignItems:'end', justifyContent:'space-between', marginBottom:24}}>
          <div>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>🔥 BESTSELLERS</div>
            <h2 style={{fontFamily:'var(--font-display)', fontSize:44, fontWeight:900, letterSpacing:'-0.03em', margin:0}}>Everyone's buying<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> these.</span></h2>
          </div>
          <div style={{display:'flex', gap:8}}>
            {['All','Earrings','Necklaces','Rings','Sets'].map((t,i)=>(
              <button key={t} style={{padding:'10px 16px', borderRadius:999, fontSize:12, fontWeight:700, background: i===0 ? 'var(--ink)' : 'transparent', color: i===0 ? '#fff' : 'var(--ink)', border: i===0 ? 'none' : '1px solid var(--line)'}}>{t}</button>
            ))}
          </div>
        </div>
        <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:16}}>
          {bestsellers.slice(0,4).map(p=>(
            <ProductCard key={p.id} product={p}/>
          ))}
        </div>
      </section>

      {/* FREE SHIPPING NUDGE BANNER */}
      <section style={{maxWidth:1440, margin:'48px auto 0', padding:'0 40px'}}>
        <div style={{background:'linear-gradient(90deg, #FFE14A 0%, #FF7A3D 100%)', borderRadius:'var(--r-xl)', padding:'40px 48px', display:'flex', alignItems:'center', justifyContent:'space-between', gap:32, position:'relative', overflow:'hidden'}}>
          <div style={{position:'absolute', right:-40, top:-40, width:300, height:300, borderRadius:999, background:'rgba(255,255,255,0.15)'}}/>
          <div style={{position:'absolute', right:60, bottom:-60, width:180, height:180, borderRadius:999, background:'rgba(255,45,111,0.3)'}}/>
          <div style={{flex:1, position:'relative'}}>
            <div style={{fontSize:12, fontWeight:800, letterSpacing:'0.1em', color:'var(--ink)', marginBottom:8}}>💝 COMBO ALERT</div>
            <h3 style={{fontFamily:'var(--font-display)', fontSize:42, fontWeight:900, lineHeight:1, letterSpacing:'-0.03em', margin:0, color:'var(--ink)'}}>
              Add 2 more = <span style={{textDecoration:'underline wavy'}}>FREE shipping</span><br/>
              <span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}>(and a free nose pin 💅)</span>
            </h3>
          </div>
          <button style={{background:'var(--ink)', color:'#fff', padding:'18px 32px', borderRadius:999, fontWeight:900, fontSize:13, letterSpacing:'0.05em', textTransform:'uppercase', whiteSpace:'nowrap', position:'relative'}}>
            Build my combo →
          </button>
        </div>
      </section>

      {/* NEW DROPS — horizontal scroll */}
      <section style={{margin:'64px 0 0'}}>
        <div style={{maxWidth:1440, margin:'0 auto', padding:'0 40px 24px', display:'flex', alignItems:'end', justifyContent:'space-between'}}>
          <div>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>✨ NEW DROPS</div>
            <h2 style={{fontFamily:'var(--font-display)', fontSize:44, fontWeight:900, letterSpacing:'-0.03em', margin:0}}>Fresh this week.</h2>
          </div>
        </div>
        <div className="no-scrollbar" style={{display:'flex', gap:16, padding:'0 40px 12px', overflowX:'auto', maxWidth:1440, margin:'0 auto'}}>
          {newDrops.map(p=>(
            <div key={p.id} style={{flex:'0 0 280px'}}>
              <ProductCard product={p}/>
            </div>
          ))}
        </div>
      </section>

      {/* SPIN THE WHEEL + REVIEWS */}
      <section style={{maxWidth:1440, margin:'64px auto 0', padding:'0 40px', display:'grid', gridTemplateColumns:'1fr 1.3fr', gap:24}}>
        {/* Spin wheel */}
        <div style={{background:'linear-gradient(135deg, #C9B6FF 0%, #FF2D6F 100%)', borderRadius:'var(--r-lg)', padding:32, color:'#fff', position:'relative', overflow:'hidden'}}>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--butter)', marginBottom:10}}>🎰 LUCKY SPIN</div>
          <h3 style={{fontFamily:'var(--font-display)', fontSize:38, fontWeight:900, lineHeight:1, letterSpacing:'-0.03em', margin:'0 0 12px'}}>Spin for up to<br/><span style={{color:'var(--butter)'}}>30% OFF</span></h3>
          <p style={{fontSize:13, opacity:0.85, marginBottom:24, maxWidth:280}}>Every new customer gets one free spin. Feeling lucky bestie?</p>
          {/* Wheel */}
          <div style={{display:'flex', alignItems:'center', gap:20}}>
            <div style={{position:'relative', width:140, height:140}}>
              <div style={{width:140, height:140, borderRadius:999, background:'conic-gradient(from 0deg, #FFE14A 0 60deg, #FF2D6F 60deg 120deg, #FFF 120deg 180deg, #FFE14A 180deg 240deg, #FF2D6F 240deg 300deg, #B8F2C9 300deg 360deg)', border:'6px solid #fff', boxShadow:'0 8px 24px rgba(0,0,0,0.2)'}}/>
              <div style={{position:'absolute', top:'50%', left:'50%', transform:'translate(-50%, -50%)', width:40, height:40, borderRadius:999, background:'#fff', color:'var(--ink)', display:'grid', placeItems:'center', fontWeight:900, fontSize:10, fontFamily:'var(--font-display)'}}>SPIN</div>
              <div style={{position:'absolute', top:-6, left:'50%', transform:'translateX(-50%)', width:0, height:0, borderLeft:'8px solid transparent', borderRight:'8px solid transparent', borderTop:'14px solid var(--ink)'}}/>
            </div>
            <button style={{background:'#fff', color:'var(--ink)', padding:'14px 22px', borderRadius:999, fontWeight:900, fontSize:12, letterSpacing:'0.05em', textTransform:'uppercase'}}>Try my luck →</button>
          </div>
        </div>

        {/* Reviews */}
        <div>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>💌 REAL REVIEWS</div>
          <h2 style={{fontFamily:'var(--font-display)', fontSize:36, fontWeight:900, letterSpacing:'-0.03em', margin:'0 0 20px'}}>"im obsessed"<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400, color:'var(--muted)'}}> — everyone</span></h2>
          <div style={{display:'grid', gridTemplateColumns:'1fr 1fr', gap:12}}>
            {REVIEWS.slice(0,4).map((r,i)=>(
              <div key={i} style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-md)', padding:16}}>
                <div style={{display:'flex', alignItems:'center', gap:10, marginBottom:10}}>
                  <div style={{width:36, height:36, borderRadius:999, background:r.color, display:'grid', placeItems:'center', fontWeight:900, fontSize:13, color:'var(--ink)'}}>{r.avatar}</div>
                  <div style={{flex:1}}>
                    <div style={{fontSize:12, fontWeight:800}}>{r.name}</div>
                    <Stars value={r.rating} size={10}/>
                  </div>
                  {r.verified && <div style={{fontSize:9, color:'var(--success)', fontWeight:700}}>✓ Verified</div>}
                </div>
                <p style={{fontSize:12, color:'var(--ink)', lineHeight:1.45, margin:'0 0 10px'}}>{r.text}</p>
                <div style={{fontSize:10, color:'var(--muted)', fontWeight:700}}>on {r.product}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* SHOP THE GRAM */}
      <section style={{maxWidth:1440, margin:'64px auto 0', padding:'0 40px'}}>
        <div style={{textAlign:'center', marginBottom:24}}>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>📸 @GENZEE.JEWELS</div>
          <h2 style={{fontFamily:'var(--font-display)', fontSize:44, fontWeight:900, letterSpacing:'-0.03em', margin:0}}>Tag us,<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> get reposted.</span></h2>
        </div>
        <div style={{display:'grid', gridTemplateColumns:'repeat(6, 1fr)', gap:8}}>
          {[1,2,3,4,5,6].map(v=>(
            <div key={v} style={{position:'relative', aspectRatio:'1/1', borderRadius:'var(--r-md)', overflow:'hidden', cursor:'pointer'}}>
              <LifestyleArt variant={v}/>
              <div style={{position:'absolute', top:10, right:10, width:24, height:24, background:'rgba(255,255,255,0.9)', borderRadius:999, display:'grid', placeItems:'center'}}>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M 6 6 L 18 18 M 6 18 L 18 6" transform="rotate(45 12 12)"/></svg>
              </div>
              <div style={{position:'absolute', left:8, bottom:8, background:'rgba(0,0,0,0.7)', color:'#fff', padding:'3px 8px', borderRadius:999, fontSize:9, fontWeight:700, backdropFilter:'blur(8px)'}}>Shop 3 items</div>
            </div>
          ))}
        </div>
      </section>

      {/* NEWSLETTER */}
      <section style={{maxWidth:1440, margin:'64px auto 0', padding:'0 40px'}}>
        <div style={{background:'var(--butter)', borderRadius:'var(--r-xl)', padding:'56px 48px', textAlign:'center', position:'relative', overflow:'hidden'}}>
          <div style={{position:'absolute', left:-40, top:-40, width:200, height:200, borderRadius:999, background:'rgba(255,45,111,0.2)'}}/>
          <div style={{position:'absolute', right:-30, bottom:-30, width:160, height:160, borderRadius:999, background:'rgba(201,182,255,0.3)'}}/>
          <div style={{position:'relative'}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--ink)', marginBottom:10}}>✉️ JOIN THE GROUP CHAT</div>
            <h2 style={{fontFamily:'var(--font-display)', fontSize:52, fontWeight:900, letterSpacing:'-0.03em', margin:'0 0 10px', color:'var(--ink)'}}>Get <span style={{color:'var(--hot)'}}>15% off</span> + early access</h2>
            <p style={{fontSize:14, color:'var(--ink)', opacity:0.7, marginBottom:24}}>Weekly drops. Exclusive codes. No spam, promise 🤞</p>
            <div style={{display:'flex', gap:0, maxWidth:480, margin:'0 auto', background:'#fff', borderRadius:999, padding:4, boxShadow:'0 8px 20px rgba(0,0,0,0.08)'}}>
              <input placeholder="your@email.com" style={{flex:1, background:'transparent', border:0, padding:'14px 18px', fontSize:14, outline:'none'}}/>
              <button style={{background:'var(--ink)', color:'#fff', borderRadius:999, padding:'14px 28px', fontSize:13, fontWeight:800, letterSpacing:'0.05em', textTransform:'uppercase'}}>Gimme 15% →</button>
            </div>
          </div>
        </div>
      </section>

      <div style={{marginTop:80}}/>
      <SiteFooter/>

      {/* Live purchase toast */}
      <div style={{position:'fixed', left:20, bottom:20, zIndex:50}}>
        <LivePurchaseToast bottom={0}/>
      </div>
    </div>
  );
}

window.HomePage = HomePage;
