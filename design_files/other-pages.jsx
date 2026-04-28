// other-pages.jsx — Cart drawer, Search, Lookbook, About

// ═══════════════════════════════════════════════════════════
// CART DRAWER (shown as a right-side overlay in a mock bg)
// ═══════════════════════════════════════════════════════════
function CartDrawerPage() {
  const cartItems = [
    { ...PRODUCTS[0], qty:1 },
    { ...PRODUCTS[5], qty:1 },
    { ...PRODUCTS[13], qty:2 },
  ];
  const subtotal = cartItems.reduce((s,i)=>s+i.price*i.qty, 0);
  const freeShipTarget = 499;
  const progress = Math.min(100, (subtotal/freeShipTarget)*100);
  const toFree = Math.max(0, freeShipTarget - subtotal);

  return (
    <div style={{position:'relative', background:'var(--paper)', minHeight:'100vh', overflow:'hidden'}}>
      {/* Fake dimmed page bg */}
      <div style={{filter:'blur(2px) brightness(0.9)', pointerEvents:'none'}}>
        <SiteHeader active="Shop"/>
        <div style={{maxWidth:1440, margin:'0 auto', padding:'40px', display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:16}}>
          {PRODUCTS.slice(0,8).map(p=><ProductCard key={p.id} product={p}/>)}
        </div>
      </div>
      <div style={{position:'absolute', inset:0, background:'rgba(10,10,10,0.5)'}}/>

      {/* Drawer */}
      <aside style={{position:'absolute', top:0, right:0, bottom:0, width:460, background:'var(--paper)', display:'flex', flexDirection:'column', boxShadow:'-20px 0 60px rgba(0,0,0,0.2)'}}>
        <div style={{padding:'20px 24px', borderBottom:'1px solid var(--line)', display:'flex', alignItems:'center', justifyContent:'space-between'}}>
          <div>
            <div style={{fontFamily:'var(--font-display)', fontSize:22, fontWeight:900, letterSpacing:'-0.02em'}}>Your bag <span style={{color:'var(--hot)'}}>(3)</span></div>
            <div style={{fontSize:11, color:'var(--muted)', marginTop:2}}>Saved items expire in 1hr ⏱️</div>
          </div>
          <button style={{width:32, height:32, borderRadius:999, background:'var(--ink)', color:'#fff', fontWeight:900}}>×</button>
        </div>

        {/* Free ship progress */}
        <div style={{padding:'16px 24px', background:'var(--butter)'}}>
          {toFree > 0 ? (
            <div style={{fontSize:12, fontWeight:800, color:'var(--ink)', marginBottom:8}}>
              Add <b style={{color:'var(--hot)'}}>₹{toFree}</b> more for FREE shipping 🚚
            </div>
          ) : (
            <div style={{fontSize:12, fontWeight:800, color:'var(--success)', marginBottom:8}}>🎉 FREE shipping unlocked!</div>
          )}
          <div style={{height:8, background:'rgba(0,0,0,0.15)', borderRadius:999, overflow:'hidden'}}>
            <div style={{width:`${progress}%`, height:'100%', background:'var(--ink)', borderRadius:999, transition:'width .3s'}}/>
          </div>
        </div>

        {/* Items */}
        <div style={{flex:1, overflowY:'auto', padding:'8px 24px'}}>
          {cartItems.map(item=>(
            <div key={item.id} style={{display:'flex', gap:12, padding:'16px 0', borderBottom:'1px solid var(--line-soft)'}}>
              <div style={{width:80, height:80, borderRadius:10, overflow:'hidden', flexShrink:0}}>
                <ProductArt product={item} size={80}/>
              </div>
              <div style={{flex:1, minWidth:0}}>
                <div style={{fontSize:10, color:'var(--muted)', fontWeight:800, letterSpacing:'0.08em', textTransform:'uppercase'}}>{item.cat}</div>
                <div style={{fontSize:13, fontWeight:800, marginTop:2}}>{item.name}</div>
                <div style={{fontSize:10, color:'var(--muted)', marginTop:2}}>Gold · Standard</div>
                <div style={{display:'flex', alignItems:'center', justifyContent:'space-between', marginTop:8}}>
                  <div style={{display:'flex', alignItems:'center', border:'1px solid var(--line)', borderRadius:999}}>
                    <button style={{padding:'4px 10px', fontWeight:900, fontSize:13}}>−</button>
                    <span style={{padding:'4px 6px', fontWeight:800, fontSize:12}}>{item.qty}</span>
                    <button style={{padding:'4px 10px', fontWeight:900, fontSize:13}}>+</button>
                  </div>
                  <div style={{display:'flex', alignItems:'baseline', gap:6}}>
                    <span style={{fontSize:14, fontWeight:900, fontFamily:'var(--font-display)'}}>₹{item.price*item.qty}</span>
                    <span style={{fontSize:10, textDecoration:'line-through', color:'var(--muted)'}}>₹{item.mrp*item.qty}</span>
                  </div>
                </div>
              </div>
            </div>
          ))}

          {/* Upsell — add to make combo */}
          <div style={{background:'#fff', border:'1.5px dashed var(--hot)', borderRadius:14, padding:14, margin:'16px 0'}}>
            <div style={{display:'flex', alignItems:'center', gap:10, marginBottom:10}}>
              <div style={{fontSize:20}}>🎁</div>
              <div>
                <div style={{fontSize:12, fontWeight:800}}>Add 1 more item — get a FREE nose pin</div>
                <div style={{fontSize:10, color:'var(--muted)'}}>Valued at ₹149 · auto-added at checkout</div>
              </div>
            </div>
            <div className="no-scrollbar" style={{display:'flex', gap:8, overflowX:'auto'}}>
              {PRODUCTS.slice(2,6).map(p=>(
                <div key={p.id} style={{flexShrink:0, width:80, position:'relative'}}>
                  <div style={{width:80, height:80, borderRadius:8, overflow:'hidden'}}><ProductArt product={p} size={80}/></div>
                  <div style={{fontSize:10, fontWeight:800, marginTop:4}}>₹{p.price}</div>
                  <button style={{position:'absolute', top:6, right:6, width:22, height:22, borderRadius:999, background:'var(--ink)', color:'#fff', fontWeight:900, fontSize:12}}>+</button>
                </div>
              ))}
            </div>
          </div>

          {/* Coupon */}
          <div style={{padding:'12px 0'}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.08em', color:'var(--muted)', marginBottom:8}}>COUPON</div>
            <div style={{display:'flex', gap:8}}>
              <input placeholder="Got a code?" style={{flex:1, border:'1px solid var(--line)', borderRadius:8, padding:'10px 12px', fontSize:12, outline:'none'}}/>
              <button style={{background:'var(--ink)', color:'#fff', borderRadius:8, padding:'10px 16px', fontWeight:800, fontSize:11}}>APPLY</button>
            </div>
            <div style={{display:'flex', gap:6, marginTop:8, flexWrap:'wrap'}}>
              {[['GENZ50','-50%'],['FIRST15','-15%'],['COMBO3','3rd free']].map(([c,d])=>(
                <div key={c} style={{border:'1px dashed var(--hot)', borderRadius:999, padding:'4px 10px', fontSize:10, fontWeight:800, color:'var(--hot)', cursor:'pointer'}}>{c} <span style={{opacity:0.6}}>{d}</span></div>
              ))}
            </div>
          </div>
        </div>

        {/* Totals */}
        <div style={{padding:'16px 24px', borderTop:'1px solid var(--line)', background:'#fff'}}>
          <div style={{display:'flex', justifyContent:'space-between', fontSize:12, marginBottom:6}}>
            <span>Subtotal</span><span style={{fontWeight:800}}>₹{subtotal}</span>
          </div>
          <div style={{display:'flex', justifyContent:'space-between', fontSize:12, marginBottom:6}}>
            <span>Shipping</span><span style={{fontWeight:800, color: toFree===0 ? 'var(--success)' : 'var(--ink)'}}>{toFree===0 ? 'FREE' : '₹49'}</span>
          </div>
          <div style={{display:'flex', justifyContent:'space-between', fontSize:12, marginBottom:12, color:'var(--success)'}}>
            <span>Discount (GENZ50)</span><span style={{fontWeight:800}}>−₹{Math.round(subtotal*0.5)}</span>
          </div>
          <div style={{display:'flex', justifyContent:'space-between', alignItems:'baseline', paddingTop:12, borderTop:'1px solid var(--line)'}}>
            <span style={{fontSize:13, fontWeight:800}}>Total</span>
            <div>
              <span style={{fontFamily:'var(--font-display)', fontSize:24, fontWeight:900, letterSpacing:'-0.02em'}}>₹{subtotal - Math.round(subtotal*0.5)}</span>
              <span style={{fontSize:11, color:'var(--muted)', marginLeft:6}}>you save ₹{Math.round(subtotal*0.5) + (toFree===0?49:0)}</span>
            </div>
          </div>
          <button style={{marginTop:14, width:'100%', background:'var(--hot)', color:'#fff', padding:'16px', borderRadius:999, fontWeight:900, fontSize:13, letterSpacing:'0.06em', textTransform:'uppercase', boxShadow:'var(--shadow-pop)'}}>
            ⚡ Checkout securely →
          </button>
          <div style={{display:'flex', justifyContent:'center', gap:6, marginTop:10}}>
            {['UPI','VISA','MC','COD'].map(p=>(
              <div key={p} style={{fontSize:9, fontWeight:800, padding:'3px 8px', border:'1px solid var(--line)', borderRadius:6, color:'var(--muted)'}}>{p}</div>
            ))}
          </div>
        </div>
      </aside>
    </div>
  );
}

// ═══════════════════════════════════════════════════════════
// SEARCH PAGE
// ═══════════════════════════════════════════════════════════
function SearchPage() {
  return (
    <div style={{background:'var(--paper)', minHeight:'100vh'}}>
      <SiteHeader active="Shop"/>
      {/* Big search */}
      <section style={{background:'var(--ink)', color:'#fff', padding:'32px 40px 48px'}}>
        <div style={{maxWidth:1200, margin:'0 auto'}}>
          <div style={{position:'relative', display:'flex', alignItems:'center', background:'#fff', borderRadius:999, padding:'4px 4px 4px 20px'}}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--ink)" strokeWidth="2.2"><circle cx="11" cy="11" r="7"/><path d="M 16 16 L 21 21"/></svg>
            <input value="gold hoops" readOnly style={{flex:1, border:0, padding:'16px 12px', fontSize:18, fontWeight:700, color:'var(--ink)', outline:'none', fontFamily:'var(--font-display)'}}/>
            <button style={{background:'var(--hot)', color:'#fff', padding:'14px 28px', borderRadius:999, fontSize:13, fontWeight:900, letterSpacing:'0.05em', textTransform:'uppercase'}}>Search</button>
          </div>
          <div style={{display:'flex', alignItems:'center', gap:10, marginTop:14, fontSize:12}}>
            <span style={{opacity:0.6}}>Trending:</span>
            {['heart hoops', 'under ₹199', 'sets combo', 'nose pins', 'evil eye', 'layered chain'].map(t=>(
              <span key={t} style={{padding:'4px 10px', background:'rgba(255,255,255,0.1)', borderRadius:999, cursor:'pointer', fontWeight:600}}>{t}</span>
            ))}
          </div>
        </div>
      </section>

      <section style={{maxWidth:1440, margin:'0 auto', padding:'28px 40px', display:'grid', gridTemplateColumns:'280px 1fr', gap:32}}>
        {/* Suggestions column */}
        <aside>
          <div style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-md)', padding:20, marginBottom:12}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--muted)', marginBottom:12}}>DID YOU MEAN</div>
            {['Gold hoop earrings','Gold huggies','Chunky gold hoops','Mini gold hoops'].map(s=>(
              <div key={s} style={{padding:'8px 0', fontSize:13, fontWeight:600, cursor:'pointer', display:'flex', alignItems:'center', gap:6}}>
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" strokeWidth="2.5"><path d="M 7 7 L 17 17 M 17 7 L 17 17 L 7 17" transform="rotate(-45 12 12)"/></svg>
                {s}
              </div>
            ))}
          </div>
          <div style={{background:'var(--butter)', borderRadius:'var(--r-md)', padding:20, position:'relative', overflow:'hidden'}}>
            <div style={{position:'absolute', bottom:-20, right:-20, width:100, height:100, opacity:0.6}}><ProductArt product={PRODUCTS[3]}/></div>
            <div style={{position:'relative'}}>
              <div style={{fontSize:10, fontWeight:800, letterSpacing:'0.1em'}}>HOT RN</div>
              <div style={{fontFamily:'var(--font-display)', fontSize:22, fontWeight:900, letterSpacing:'-0.02em', lineHeight:1, marginTop:6}}>Y2K Chrome Hoops</div>
              <div style={{fontSize:16, fontWeight:900, marginTop:40, fontFamily:'var(--font-display)'}}>₹349 <span style={{fontSize:11, textDecoration:'line-through', color:'rgba(0,0,0,0.5)'}}>₹699</span></div>
            </div>
          </div>
        </aside>

        {/* Results */}
        <div>
          <div style={{display:'flex', alignItems:'baseline', justifyContent:'space-between', marginBottom:20}}>
            <div>
              <h1 style={{fontFamily:'var(--font-display)', fontSize:32, fontWeight:900, letterSpacing:'-0.03em', margin:0}}>Showing <span style={{color:'var(--hot)'}}>24</span> results for "gold hoops"</h1>
              <div style={{fontSize:12, color:'var(--muted)', marginTop:4}}>Sorted by relevance · 18 under ₹299</div>
            </div>
            <button style={{fontSize:12, fontWeight:700}}>Sort ↕</button>
          </div>

          {/* Filter chips */}
          <div style={{display:'flex', gap:8, marginBottom:20, flexWrap:'wrap'}}>
            {[['Earrings','active'],['Under ₹299',''],['Gold',''],['In stock','']].map(([l,s])=>(
              <div key={l} style={{padding:'8px 14px', borderRadius:999, background: s==='active' ? 'var(--ink)' : '#fff', color: s==='active' ? '#fff' : 'var(--ink)', border: s==='active' ? 'none' : '1px solid var(--line)', fontSize:12, fontWeight:700, display:'flex', alignItems:'center', gap:6}}>
                {l}{s==='active' && <span style={{fontSize:14, opacity:0.7}}>×</span>}
              </div>
            ))}
            <button style={{padding:'8px 14px', borderRadius:999, color:'var(--hot)', fontSize:12, fontWeight:700}}>Clear all</button>
          </div>

          <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr)', gap:16}}>
            {[PRODUCTS[0], PRODUCTS[1], PRODUCTS[2], PRODUCTS[3], PRODUCTS[5], PRODUCTS[7]].map(p=>(
              <ProductCard key={p.id} product={p}/>
            ))}
          </div>

          {/* Zero-result fallback preview section */}
          <div style={{marginTop:40, padding:'32px', background:'#fff', border:'1px dashed var(--line)', borderRadius:'var(--r-lg)', textAlign:'center'}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--muted)'}}>CAN'T FIND IT?</div>
            <div style={{fontFamily:'var(--font-display)', fontSize:26, fontWeight:900, letterSpacing:'-0.02em', marginTop:8}}>DM us on IG, we'll source it ✨</div>
            <button style={{marginTop:16, background:'var(--ink)', color:'#fff', padding:'12px 24px', borderRadius:999, fontSize:12, fontWeight:800, letterSpacing:'0.05em', textTransform:'uppercase'}}>@genzee.jewels →</button>
          </div>
        </div>
      </section>
      <SiteFooter/>
    </div>
  );
}

// ═══════════════════════════════════════════════════════════
// LOOKBOOK
// ═══════════════════════════════════════════════════════════
function LookbookPage() {
  const looks = [
    { id:1, title:'Coquette Core', subtitle:'ribbons, pearls, main character', items:[PRODUCTS[1], PRODUCTS[15], PRODUCTS[6]], color:'#FFDCE6' },
    { id:2, title:'Clean Girl Edit', subtitle:'dainty, gold, quietly expensive', items:[PRODUCTS[5], PRODUCTS[0], PRODUCTS[13]], color:'#F4EFE6' },
    { id:3, title:'Y2K Revival', subtitle:'chrome on chrome on chrome', items:[PRODUCTS[3], PRODUCTS[6], PRODUCTS[8]], color:'#C9B6FF' },
    { id:4, title:'Concert Ready', subtitle:'stars, studs, statement sparkle', items:[PRODUCTS[4], PRODUCTS[2], PRODUCTS[12]], color:'#0A0A0A' },
  ];
  return (
    <div style={{background:'var(--paper)', minHeight:'100vh'}}>
      <SiteHeader active="Lookbook"/>

      {/* Hero */}
      <section style={{padding:'40px 40px 24px', textAlign:'center'}}>
        <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)'}}>THE LOOKBOOK · SUMMER '26</div>
        <h1 style={{fontFamily:'var(--font-display)', fontSize:96, fontWeight:900, letterSpacing:'-0.04em', lineHeight:0.95, margin:'8px 0 0'}}>Shop the<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> vibe.</span></h1>
        <p style={{fontSize:14, color:'var(--muted)', maxWidth:480, margin:'14px auto 0', lineHeight:1.5}}>Four moods. Four mini-capsules. Add the whole look to cart with one tap because decisions are hard.</p>
      </section>

      {/* Looks */}
      <section style={{maxWidth:1440, margin:'0 auto', padding:'0 40px'}}>
        {looks.map((look, idx)=>(
          <div key={look.id} style={{display:'grid', gridTemplateColumns: idx%2===0 ? '1.2fr 1fr' : '1fr 1.2fr', gap:24, marginBottom:32, alignItems:'stretch'}}>
            {idx%2===1 && <LookContent look={look}/>}
            <div style={{position:'relative', borderRadius:'var(--r-xl)', overflow:'hidden', background:look.color, aspectRatio:'4/5', minHeight:480}}>
              <LifestyleArt variant={look.id}/>
              <div style={{position:'absolute', inset:0, background:'linear-gradient(180deg, transparent 50%, rgba(0,0,0,0.4) 100%)'}}/>
              {/* Hotspots */}
              {look.items.map((item, i)=>{
                const positions = [
                  {top:'25%', left:'38%'},
                  {top:'50%', left:'58%'},
                  {top:'70%', left:'40%'},
                ];
                return (
                  <div key={item.id} style={{position:'absolute', ...positions[i]}}>
                    <div style={{width:32, height:32, borderRadius:999, background:'#fff', boxShadow:'0 4px 12px rgba(0,0,0,0.2)', display:'grid', placeItems:'center', fontWeight:900, fontSize:12, cursor:'pointer', position:'relative'}}>
                      <span className="pulse-dot" style={{position:'absolute', inset:0, borderRadius:999, background:'var(--hot)', opacity:0.3}}/>
                      {i+1}
                    </div>
                  </div>
                );
              })}
              <div style={{position:'absolute', left:24, bottom:24, color:'#fff'}}>
                <Pill bg="var(--butter)" color="var(--ink)">LOOK 0{look.id}</Pill>
              </div>
            </div>
            {idx%2===0 && <LookContent look={look}/>}
          </div>
        ))}
      </section>

      {/* CTA */}
      <section style={{padding:'80px 40px', textAlign:'center'}}>
        <h2 style={{fontFamily:'var(--font-display)', fontSize:52, fontWeight:900, letterSpacing:'-0.04em', margin:0}}>Styled you. Now<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> style us.</span></h2>
        <p style={{fontSize:14, color:'var(--muted)', marginTop:14}}>Tag <b>@genzee.jewels</b> for a chance to be featured + 20% off your next order.</p>
      </section>
      <SiteFooter/>
    </div>
  );
}
function LookContent({ look }) {
  const total = look.items.reduce((s,i)=>s+i.price,0);
  const mrpTotal = look.items.reduce((s,i)=>s+i.mrp,0);
  return (
    <div style={{display:'flex', flexDirection:'column', justifyContent:'center', padding:'32px'}}>
      <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)'}}>LOOK 0{look.id}</div>
      <h2 style={{fontFamily:'var(--font-display)', fontSize:56, fontWeight:900, letterSpacing:'-0.04em', lineHeight:0.95, margin:'8px 0 6px'}}>{look.title}</h2>
      <div style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontSize:18, color:'var(--muted)', marginBottom:20}}>{look.subtitle}</div>
      <div style={{display:'flex', flexDirection:'column', gap:10, marginBottom:20}}>
        {look.items.map((item,i)=>(
          <div key={item.id} style={{display:'flex', alignItems:'center', gap:12, padding:10, background:'#fff', borderRadius:14, border:'1px solid var(--line)'}}>
            <div style={{width:28, height:28, borderRadius:999, background:'var(--ink)', color:'var(--butter)', display:'grid', placeItems:'center', fontWeight:900, fontSize:12}}>{i+1}</div>
            <div style={{width:56, height:56, borderRadius:8, overflow:'hidden'}}><ProductArt product={item} size={56}/></div>
            <div style={{flex:1}}>
              <div style={{fontSize:13, fontWeight:800}}>{item.name}</div>
              <div style={{fontSize:11, color:'var(--muted)'}}>₹{item.price} <span style={{textDecoration:'line-through'}}>₹{item.mrp}</span></div>
            </div>
            <button style={{background:'var(--ink)', color:'#fff', padding:'8px 12px', borderRadius:999, fontWeight:800, fontSize:10, letterSpacing:'0.05em'}}>+ ADD</button>
          </div>
        ))}
      </div>
      <div style={{padding:'16px 20px', background:'var(--ink)', color:'#fff', borderRadius:14, display:'flex', alignItems:'center', justifyContent:'space-between'}}>
        <div>
          <div style={{fontSize:10, color:'var(--butter)', fontWeight:800, letterSpacing:'0.08em'}}>SHOP THE WHOLE LOOK</div>
          <div style={{display:'flex', alignItems:'baseline', gap:8, marginTop:4}}>
            <span style={{fontFamily:'var(--font-display)', fontSize:26, fontWeight:900}}>₹{total}</span>
            <span style={{fontSize:12, textDecoration:'line-through', opacity:0.5}}>₹{mrpTotal}</span>
            <span style={{fontSize:10, fontWeight:800, color:'var(--butter)'}}>SAVE ₹{mrpTotal-total}</span>
          </div>
        </div>
        <button style={{background:'var(--hot)', color:'#fff', padding:'12px 20px', borderRadius:999, fontWeight:900, fontSize:11, letterSpacing:'0.05em', textTransform:'uppercase'}}>Add all →</button>
      </div>
    </div>
  );
}

// ═══════════════════════════════════════════════════════════
// ABOUT PAGE
// ═══════════════════════════════════════════════════════════
function AboutPage() {
  return (
    <div style={{background:'var(--paper)', minHeight:'100vh'}}>
      <SiteHeader active="About"/>

      {/* Hero */}
      <section style={{padding:'60px 40px 0', textAlign:'center', maxWidth:900, margin:'0 auto'}}>
        <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)'}}>THE GENZEE STORY</div>
        <h1 style={{fontFamily:'var(--font-display)', fontSize:84, fontWeight:900, letterSpacing:'-0.04em', lineHeight:0.95, margin:'10px 0 0'}}>Good jewelry<br/><span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}>shouldn't cost</span><br/>your rent<span style={{color:'var(--hot)'}}>.</span></h1>
        <p style={{fontSize:16, color:'var(--muted)', lineHeight:1.6, marginTop:20, maxWidth:640, margin:'20px auto 0'}}>We started Genzee in 2023 because we were tired of paying ₹2,000 for earrings that turned green in a week — and also tired of the ₹99 ones that looked like they belonged in a party-favor bag.</p>
      </section>

      {/* Giant image */}
      <section style={{maxWidth:1440, margin:'40px auto 0', padding:'0 40px'}}>
        <div style={{borderRadius:'var(--r-xl)', overflow:'hidden', aspectRatio:'21/9', position:'relative'}}>
          <LifestyleArt variant={2}/>
          <div style={{position:'absolute', inset:0, background:'linear-gradient(180deg, transparent 50%, rgba(0,0,0,0.35) 100%)'}}/>
          <div style={{position:'absolute', left:40, bottom:32, color:'#fff'}}>
            <Pill bg="var(--butter)" color="var(--ink)">JAIPUR, INDIA · EST 2023</Pill>
          </div>
        </div>
      </section>

      {/* Stats strip */}
      <section style={{maxWidth:1440, margin:'40px auto 0', padding:'0 40px'}}>
        <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:16}}>
          {[
            {n:'2.4M+', l:'pieces shipped', c:'var(--butter)'},
            {n:'180K+', l:'happy besties', c:'var(--hot)', cc:'#fff'},
            {n:'4.8/5', l:'avg rating', c:'var(--lilac)'},
            {n:'<₹499', l:'on everything', c:'var(--ink)', cc:'#fff'},
          ].map(s=>(
            <div key={s.l} style={{background:s.c, color:s.cc||'var(--ink)', borderRadius:'var(--r-lg)', padding:28, textAlign:'center'}}>
              <div style={{fontFamily:'var(--font-display)', fontSize:56, fontWeight:900, letterSpacing:'-0.03em', lineHeight:1}}>{s.n}</div>
              <div style={{fontSize:12, fontWeight:700, opacity:0.7, marginTop:6, letterSpacing:'0.05em', textTransform:'uppercase'}}>{s.l}</div>
            </div>
          ))}
        </div>
      </section>

      {/* Values */}
      <section style={{maxWidth:1200, margin:'80px auto 0', padding:'0 40px'}}>
        <h2 style={{fontFamily:'var(--font-display)', fontSize:52, fontWeight:900, letterSpacing:'-0.04em', textAlign:'center', margin:'0 0 40px'}}>What we <span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}>actually</span> stand for.</h2>
        <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr)', gap:20}}>
          {[
            {n:'01', t:'Quality you can trust', d:'18k gold plating over brass. Hypoallergenic, nickel-free. 6-month tarnish guarantee. Period.', c:'var(--butter)'},
            {n:'02', t:'Prices that make sense', d:"We cut out 6 middlemen, 3 designer egos and 1 fancy marketing agency. That's how we get to ₹199.", c:'var(--lilac)'},
            {n:'03', t:'Packaging worth posting', d:'Every order arrives in a hot pink box with a handwritten thank-you, a freebie, and way too many stickers.', c:'var(--mint)'},
          ].map(v=>(
            <div key={v.n} style={{background:v.c, borderRadius:'var(--r-xl)', padding:28}}>
              <div style={{fontFamily:'var(--font-display)', fontSize:72, fontWeight:900, letterSpacing:'-0.04em', lineHeight:0.9, color:'var(--ink)', opacity:0.3}}>{v.n}</div>
              <div style={{fontFamily:'var(--font-display)', fontSize:24, fontWeight:900, letterSpacing:'-0.02em', margin:'16px 0 10px'}}>{v.t}</div>
              <p style={{fontSize:14, lineHeight:1.5, margin:0, color:'var(--ink)', opacity:0.8}}>{v.d}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Press strip */}
      <section style={{maxWidth:1200, margin:'80px auto 0', padding:'40px', background:'var(--ink)', borderRadius:'var(--r-xl)', color:'#fff'}}>
        <div style={{textAlign:'center', fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--butter)', marginBottom:24}}>AS SEEN IN</div>
        <div style={{display:'flex', justifyContent:'space-around', alignItems:'center', flexWrap:'wrap', gap:20}}>
          {['VOGUE INDIA', 'ELLE', 'YOUR STORY', 'LBB', 'COSMOPOLITAN', 'GRAZIA'].map(m=>(
            <div key={m} style={{fontFamily:'var(--font-serif)', fontSize:22, fontWeight:500, letterSpacing:'-0.01em', opacity:0.8}}>{m}</div>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section style={{maxWidth:900, margin:'80px auto 0', padding:'40px', textAlign:'center'}}>
        <div style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontSize:22, color:'var(--muted)'}}>— from founders Aanya & Kabir</div>
        <h2 style={{fontFamily:'var(--font-display)', fontSize:44, fontWeight:900, letterSpacing:'-0.03em', margin:'12px 0 20px'}}>Come for the jewelry.<br/>Stay for the group chat energy.</h2>
        <button style={{background:'var(--hot)', color:'#fff', padding:'18px 32px', borderRadius:999, fontWeight:900, fontSize:14, letterSpacing:'0.05em', textTransform:'uppercase', boxShadow:'var(--shadow-pop)'}}>Shop the drop →</button>
      </section>

      <div style={{marginTop:80}}/>
      <SiteFooter/>
    </div>
  );
}

window.CartDrawerPage = CartDrawerPage;
window.SearchPage = SearchPage;
window.LookbookPage = LookbookPage;
window.AboutPage = AboutPage;
