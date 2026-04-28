// collection.jsx — Collection / category listing page

function CollectionPage() {
  const [sortBy, setSortBy] = React.useState('Trending');
  const [view, setView] = React.useState('grid4');
  const [priceRange, setPriceRange] = React.useState([0, 499]);

  const products = PRODUCTS.filter(p=>p.price >= priceRange[0] && p.price <= priceRange[1]);

  return (
    <div style={{background:'var(--paper)', minHeight:'100vh'}}>
      <SiteHeader active="Shop"/>

      {/* Hero banner for collection */}
      <section style={{background:'linear-gradient(135deg, #FFE14A 0%, #FF7A3D 50%, #FF2D6F 100%)', padding:'40px 40px 32px', position:'relative', overflow:'hidden'}}>
        <div style={{position:'absolute', right:40, top:20, width:220, height:220, opacity:0.3}}>
          <ProductArt product={{...PRODUCTS[0], color:'transparent'}}/>
        </div>
        <div style={{maxWidth:1440, margin:'0 auto', position:'relative'}}>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--ink)', marginBottom:8}}>HOME / SHOP / EARRINGS</div>
          <h1 style={{fontFamily:'var(--font-display)', fontSize:96, fontWeight:900, letterSpacing:'-0.04em', margin:0, lineHeight:0.9, color:'var(--ink)'}}>Earrings<span style={{color:'var(--hot)'}}>.</span></h1>
          <div style={{display:'flex', alignItems:'center', gap:16, marginTop:16}}>
            <div style={{fontSize:14, color:'var(--ink)', fontWeight:700}}>128 styles · all under ₹499 · ships in 24hrs</div>
            <Pill bg="var(--ink)" color="var(--butter)">🔥 47 sold in last hour</Pill>
          </div>
        </div>
      </section>

      {/* Sticky filter bar */}
      <div style={{background:'#fff', borderBottom:'1px solid var(--line)', position:'sticky', top:58, zIndex:30}}>
        <div style={{maxWidth:1440, margin:'0 auto', padding:'12px 40px', display:'flex', alignItems:'center', justifyContent:'space-between', gap:16}}>
          <div className="no-scrollbar" style={{display:'flex', gap:8, overflowX:'auto'}}>
            {[
              {label:'All', count:128, active:true},
              {label:'Under ₹199', count:34, hot:true},
              {label:'Under ₹299', count:67, hot:true},
              {label:'Bestseller', count:24},
              {label:'New', count:18},
              {label:'Gold', count:82},
              {label:'Silver', count:46},
              {label:'Hoops', count:28},
              {label:'Studs', count:42},
              {label:'Drops', count:31},
            ].map(c=>(
              <button key={c.label} style={{
                padding:'8px 14px', borderRadius:999, whiteSpace:'nowrap',
                border: c.active ? 'none' : '1px solid var(--line)',
                background: c.active ? 'var(--ink)' : c.hot ? 'var(--butter)' : '#fff',
                color: c.active ? '#fff' : 'var(--ink)',
                fontSize:12, fontWeight:700,
              }}>
                {c.label} <span style={{opacity:0.5, marginLeft:4}}>{c.count}</span>
              </button>
            ))}
          </div>
          <div style={{display:'flex', alignItems:'center', gap:12, flexShrink:0}}>
            <button style={{fontSize:12, fontWeight:700, display:'flex', alignItems:'center', gap:4}}>
              Sort: <b>{sortBy}</b>
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3"><path d="M 6 9 L 12 15 L 18 9"/></svg>
            </button>
            <div style={{width:1, height:16, background:'var(--line)'}}/>
            <button onClick={()=>setView('grid3')} style={{opacity: view==='grid3'?1:0.4}}>
              <svg width="16" height="16" viewBox="0 0 16 16"><rect x="0" y="0" width="4" height="4"/><rect x="6" y="0" width="4" height="4"/><rect x="12" y="0" width="4" height="4"/><rect x="0" y="6" width="4" height="4"/><rect x="6" y="6" width="4" height="4"/><rect x="12" y="6" width="4" height="4"/></svg>
            </button>
            <button onClick={()=>setView('grid4')} style={{opacity: view==='grid4'?1:0.4}}>
              <svg width="16" height="16" viewBox="0 0 16 16"><rect x="0" y="0" width="3" height="3"/><rect x="4.5" y="0" width="3" height="3"/><rect x="9" y="0" width="3" height="3"/><rect x="13.5" y="0" width="3" height="3"/><rect x="0" y="4.5" width="3" height="3"/><rect x="4.5" y="4.5" width="3" height="3"/><rect x="9" y="4.5" width="3" height="3"/><rect x="13.5" y="4.5" width="3" height="3"/></svg>
            </button>
          </div>
        </div>
      </div>

      {/* Main grid */}
      <div style={{maxWidth:1440, margin:'0 auto', padding:'24px 40px', display:'grid', gridTemplateColumns:'240px 1fr', gap:32}}>
        {/* Sidebar filters */}
        <aside style={{position:'sticky', top:130, alignSelf:'start'}}>
          <div style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-md)', padding:20, marginBottom:16}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--muted)', marginBottom:12}}>PRICE</div>
            <div style={{display:'flex', justifyContent:'space-between', fontSize:13, fontWeight:800, marginBottom:10}}>
              <span>₹{priceRange[0]}</span><span>₹{priceRange[1]}</span>
            </div>
            <div style={{position:'relative', height:4, background:'var(--line)', borderRadius:999, marginBottom:14}}>
              <div style={{position:'absolute', left:`${(priceRange[0]/499)*100}%`, right:`${100-(priceRange[1]/499)*100}%`, top:0, bottom:0, background:'var(--hot)', borderRadius:999}}/>
              <div style={{position:'absolute', left:`${(priceRange[0]/499)*100}%`, top:-5, width:14, height:14, borderRadius:999, background:'#fff', border:'3px solid var(--hot)', transform:'translateX(-50%)'}}/>
              <div style={{position:'absolute', left:`${(priceRange[1]/499)*100}%`, top:-5, width:14, height:14, borderRadius:999, background:'#fff', border:'3px solid var(--hot)', transform:'translateX(-50%)'}}/>
            </div>
            <div style={{display:'grid', gridTemplateColumns:'1fr 1fr', gap:6}}>
              {[[0,199],[0,299],[300,499]].map(([a,b])=>(
                <button key={`${a}-${b}`} onClick={()=>setPriceRange([a,b])} style={{padding:'6px', borderRadius:6, border:'1px solid var(--line)', background: priceRange[0]===a && priceRange[1]===b ? 'var(--ink)' : '#fff', color: priceRange[0]===a && priceRange[1]===b ? '#fff':'var(--ink)', fontSize:10, fontWeight:700}}>₹{a}-{b}</button>
              ))}
            </div>
          </div>

          {[
            { t:'CATEGORY', items:[['Studs',42],['Hoops',28],['Drops',31],['Huggies',18],['Ear Cuffs',9]] },
            { t:'METAL', items:[['Gold-plated',82],['Silver',46],['Rose Gold',24],['Oxidized',12]] },
            { t:'COLOR', swatches:['#FFE14A','#FF2D6F','#C9B6FF','#B8F2C9','#FF7A3D','#0A0A0A','#F4EFE6','#3B82F6'] },
            { t:'RATING', items:[['4★ & up',98],['3★ & up',124]] },
          ].map(g=>(
            <div key={g.t} style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-md)', padding:20, marginBottom:12}}>
              <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--muted)', marginBottom:12}}>{g.t}</div>
              {g.swatches ? (
                <div style={{display:'flex', flexWrap:'wrap', gap:8}}>
                  {g.swatches.map(c=>(
                    <div key={c} style={{width:26, height:26, borderRadius:999, background:c, border:'1px solid var(--line)', cursor:'pointer'}}/>
                  ))}
                </div>
              ) : g.items.map(([name,n])=>(
                <label key={name} style={{display:'flex', alignItems:'center', gap:8, fontSize:13, marginBottom:8, cursor:'pointer'}}>
                  <input type="checkbox" style={{accentColor:'var(--hot)'}}/>
                  <span style={{flex:1}}>{name}</span>
                  <span style={{fontSize:11, color:'var(--muted)'}}>{n}</span>
                </label>
              ))}
            </div>
          ))}

          {/* Freebie nudge in sidebar */}
          <div style={{background:'var(--butter)', borderRadius:'var(--r-md)', padding:18, position:'relative', overflow:'hidden'}}>
            <div style={{fontSize:10, fontWeight:800, letterSpacing:'0.08em', color:'var(--ink)'}}>🎁 FREEBIE</div>
            <div style={{fontFamily:'var(--font-display)', fontSize:20, fontWeight:900, lineHeight:1, letterSpacing:'-0.02em', marginTop:4}}>Spend ₹799,<br/>get a nose pin<br/>free.</div>
          </div>
        </aside>

        {/* Product grid */}
        <div>
          {/* Mixed grid with promo cards interspersed */}
          <div style={{display:'grid', gridTemplateColumns: view==='grid4' ? 'repeat(4, 1fr)' : 'repeat(3, 1fr)', gap:16}}>
            {products.slice(0,4).map(p=><ProductCard key={p.id} product={p}/>)}

            {/* Promo card */}
            <div style={{gridColumn:'span 2', borderRadius:'var(--r-md)', overflow:'hidden', background:'var(--ink)', color:'#fff', padding:28, position:'relative', display:'flex', flexDirection:'column', justifyContent:'space-between'}}>
              <div>
                <Pill bg="var(--hot)" color="#fff">⚡ FLASH</Pill>
                <h3 style={{fontFamily:'var(--font-display)', fontSize:36, fontWeight:900, lineHeight:1, letterSpacing:'-0.03em', margin:'14px 0 10px'}}>Buy 2<br/>get 1 <span style={{color:'var(--butter)'}}>FREE</span></h3>
                <p style={{fontSize:12, opacity:0.7, maxWidth:220}}>Mix & match from any category. Cheapest item is free.</p>
              </div>
              <div>
                <div style={{fontSize:10, opacity:0.6, marginBottom:6}}>ENDS IN</div>
                <CountdownTimer seconds={5280} compact/>
              </div>
              <div style={{position:'absolute', top:20, right:20, width:110, height:110, opacity:0.4}}>
                <ProductArt product={PRODUCTS[2]}/>
              </div>
            </div>

            {products.slice(4,10).map(p=><ProductCard key={p.id} product={p}/>)}

            {/* Customer photo promo */}
            <div style={{borderRadius:'var(--r-md)', overflow:'hidden', position:'relative', cursor:'pointer'}}>
              <div style={{aspectRatio:'3/4'}}><LifestyleArt variant={3}/></div>
              <div style={{position:'absolute', inset:0, background:'linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.7) 100%)'}}/>
              <div style={{position:'absolute', left:14, right:14, bottom:14, color:'#fff'}}>
                <Pill bg="var(--butter)" color="var(--ink)" size="sm">📸 @ishaa.b</Pill>
                <div style={{fontFamily:'var(--font-display)', fontSize:18, fontWeight:900, letterSpacing:'-0.02em', marginTop:8}}>Shop the look</div>
                <div style={{fontSize:11, opacity:0.8, marginTop:2}}>3 items · from ₹199</div>
              </div>
            </div>

            {products.slice(10,16).map(p=><ProductCard key={p.id} product={p}/>)}
          </div>

          {/* Pagination */}
          <div style={{display:'flex', justifyContent:'center', alignItems:'center', gap:6, marginTop:40}}>
            <button style={{width:36, height:36, borderRadius:10, border:'1px solid var(--line)', background:'#fff'}}>←</button>
            {[1,2,3,4,5].map(n=>(
              <button key={n} style={{width:36, height:36, borderRadius:10, background: n===1 ? 'var(--ink)' : '#fff', color: n===1?'#fff':'var(--ink)', fontWeight:800, fontSize:13, border: n===1 ? 'none' : '1px solid var(--line)'}}>{n}</button>
            ))}
            <span style={{padding:'0 8px', color:'var(--muted)'}}>…</span>
            <button style={{width:36, height:36, borderRadius:10, border:'1px solid var(--line)', background:'#fff', fontWeight:800, fontSize:13}}>11</button>
            <button style={{width:36, height:36, borderRadius:10, border:'1px solid var(--line)', background:'#fff'}}>→</button>
          </div>
        </div>
      </div>

      <SiteFooter/>
    </div>
  );
}

window.CollectionPage = CollectionPage;
