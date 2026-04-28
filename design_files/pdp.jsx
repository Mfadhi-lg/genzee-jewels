// pdp.jsx — Product Detail Page

function PDPPage() {
  const product = PRODUCTS[0];
  const [qty, setQty] = React.useState(1);
  const [variant, setVariant] = React.useState('gold');
  const [mainImg, setMainImg] = React.useState(0);
  const related = PRODUCTS.filter(p=>p.id !== product.id).slice(0,4);

  return (
    <div style={{background:'var(--paper)', minHeight:'100vh'}}>
      <SiteHeader active="Shop"/>

      {/* Breadcrumb */}
      <div style={{maxWidth:1440, margin:'0 auto', padding:'20px 40px 0'}}>
        <div style={{fontSize:11, fontWeight:700, color:'var(--muted)', letterSpacing:'0.08em'}}>HOME / EARRINGS / <span style={{color:'var(--ink)'}}>BUTTER HEART HUGGIES</span></div>
      </div>

      <section style={{maxWidth:1440, margin:'0 auto', padding:'20px 40px', display:'grid', gridTemplateColumns:'80px 1fr 440px', gap:24}}>
        {/* Thumbs */}
        <div style={{display:'flex', flexDirection:'column', gap:10}}>
          {[0,1,2,3,4].map(i=>(
            <div key={i} onClick={()=>setMainImg(i)} style={{borderRadius:10, overflow:'hidden', aspectRatio:'1/1', border: mainImg===i ? '2px solid var(--ink)' : '2px solid transparent', cursor:'pointer'}}>
              {i < 3 ? <ProductArt product={{...product, color: [product.color, '#F4EFE6', '#FF2D6F'][i] || product.color}}/> : <LifestyleArt variant={i-1}/>}
            </div>
          ))}
        </div>

        {/* Main image */}
        <div>
          <div style={{position:'relative', borderRadius:'var(--r-lg)', overflow:'hidden', aspectRatio:'1/1', background:'#fff'}}>
            {mainImg < 3 ? <ProductArt product={{...product, color: [product.color, '#F4EFE6', '#FF2D6F'][mainImg] || product.color}}/> : <LifestyleArt variant={mainImg-1}/>}
            <div style={{position:'absolute', top:16, left:16, display:'flex', flexDirection:'column', gap:6}}>
              <Pill bg="var(--hot)" color="#fff">🔥 BESTSELLER</Pill>
              <Pill bg="var(--butter)" color="var(--ink)">50% OFF</Pill>
            </div>
            <div style={{position:'absolute', top:16, right:16, background:'rgba(255,255,255,0.9)', padding:'6px 10px', borderRadius:999, fontSize:11, fontWeight:800, backdropFilter:'blur(6px)'}}>
              <span className="pulse-dot" style={{display:'inline-block', width:6, height:6, borderRadius:999, background:'var(--hot)', marginRight:5}}/>
              147 viewing now
            </div>
          </div>
          {/* Customer wearing strip */}
          <div style={{marginTop:12, display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:8}}>
            {[1,2,3,5].map(v=>(
              <div key={v} style={{aspectRatio:'1/1', borderRadius:'var(--r-sm)', overflow:'hidden'}}>
                <LifestyleArt variant={v}/>
              </div>
            ))}
          </div>
          <div style={{fontSize:11, fontWeight:700, color:'var(--muted)', marginTop:8, textAlign:'center'}}>See how 2,847 people wore it →</div>
        </div>

        {/* Buy box */}
        <aside>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>GENZEE ESSENTIALS</div>
          <h1 style={{fontFamily:'var(--font-display)', fontSize:40, fontWeight:900, letterSpacing:'-0.03em', lineHeight:1, margin:'0 0 12px'}}>Butter Heart<br/>Huggies</h1>
          <div style={{display:'flex', alignItems:'center', gap:10, marginBottom:16}}>
            <Stars value={4.8} size={14}/>
            <span style={{fontSize:12, fontWeight:700}}>4.8</span>
            <span style={{fontSize:12, color:'var(--muted)'}}>(1,284 reviews)</span>
            <span style={{fontSize:12, color:'var(--success)', fontWeight:700}}>· 2.4k sold</span>
          </div>

          <Price price={299} mrp={599} size="lg"/>
          <div style={{fontSize:11, color:'var(--muted)', marginTop:6}}>Inclusive of all taxes · Tax-free over ₹499</div>

          {/* Countdown */}
          <div style={{marginTop:20, padding:'14px 16px', background:'var(--ink)', borderRadius:14, display:'flex', alignItems:'center', justifyContent:'space-between', color:'#fff'}}>
            <div>
              <div style={{fontSize:10, color:'var(--butter)', fontWeight:800, letterSpacing:'0.1em'}}>⚡ DEAL ENDS IN</div>
              <div style={{fontSize:12, opacity:0.7, marginTop:2}}>Price goes back up after</div>
            </div>
            <CountdownTimer seconds={7245} compact/>
          </div>

          {/* Low stock */}
          <div style={{marginTop:14, padding:'10px 14px', background:'#FFEEF3', borderRadius:10, display:'flex', alignItems:'center', gap:8}}>
            <span className="pulse-dot" style={{width:8, height:8, borderRadius:999, background:'var(--hot)'}}/>
            <span style={{fontSize:12, fontWeight:700, color:'var(--hot)'}}>Only 3 left in stock — selling fast!</span>
          </div>

          {/* Color variant */}
          <div style={{marginTop:22}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.08em', color:'var(--muted)', marginBottom:10}}>METAL: <span style={{color:'var(--ink)'}}>GOLD</span></div>
            <div style={{display:'flex', gap:8}}>
              {[
                {k:'gold', c:'#E6B84D', label:'Gold'},
                {k:'silver', c:'#C8CCD4', label:'Silver'},
                {k:'rose', c:'#E8B4A0', label:'Rose'},
              ].map(v=>(
                <button key={v.k} onClick={()=>setVariant(v.k)} style={{
                  display:'flex', alignItems:'center', gap:8, padding:'8px 12px 8px 8px', borderRadius:999,
                  border: variant===v.k ? '2px solid var(--ink)' : '2px solid var(--line)', background:'#fff', fontSize:12, fontWeight:700,
                }}>
                  <span style={{width:20, height:20, borderRadius:999, background:v.c}}/>
                  {v.label}
                </button>
              ))}
            </div>
          </div>

          {/* Qty */}
          <div style={{marginTop:22, display:'flex', alignItems:'center', gap:14}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.08em', color:'var(--muted)'}}>QTY</div>
            <div style={{display:'flex', alignItems:'center', border:'1px solid var(--line)', borderRadius:999}}>
              <button onClick={()=>setQty(Math.max(1,qty-1))} style={{padding:'8px 14px', fontWeight:900}}>−</button>
              <span style={{padding:'8px 4px', minWidth:24, textAlign:'center', fontWeight:800}}>{qty}</span>
              <button onClick={()=>setQty(qty+1)} style={{padding:'8px 14px', fontWeight:900}}>+</button>
            </div>
            <div style={{fontSize:11, color:'var(--muted)'}}>Max 5 per order</div>
          </div>

          {/* CTAs */}
          <div style={{marginTop:22, display:'flex', flexDirection:'column', gap:10}}>
            <button style={{background:'var(--ink)', color:'#fff', padding:'18px', borderRadius:999, fontWeight:900, fontSize:14, letterSpacing:'0.05em', textTransform:'uppercase', display:'flex', alignItems:'center', justifyContent:'center', gap:8}}>
              Add to cart · ₹{299*qty}
            </button>
            <button style={{background:'var(--hot)', color:'#fff', padding:'18px', borderRadius:999, fontWeight:900, fontSize:14, letterSpacing:'0.05em', textTransform:'uppercase', display:'flex', alignItems:'center', justifyContent:'center', gap:8, boxShadow:'var(--shadow-pop)'}}>
              ⚡ Buy it now
            </button>
          </div>

          {/* Offers */}
          <div style={{marginTop:20, border:'1.5px dashed var(--line)', borderRadius:14, padding:16}}>
            <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.08em', color:'var(--hot)', marginBottom:10}}>🎁 OFFERS YOU UNLOCK</div>
            {[
              {b:'💸', t:'Extra 10% off on prepaid orders', c:'GENZ10'},
              {b:'🎁', t:'FREE nose pin on orders above ₹799', c:null},
              {b:'💝', t:'Buy 2 sets, get 3rd FREE', c:'COMBO3'},
            ].map(o=>(
              <div key={o.t} style={{display:'flex', alignItems:'center', gap:10, padding:'8px 0', fontSize:12}}>
                <div style={{fontSize:16}}>{o.b}</div>
                <div style={{flex:1}}>
                  <div style={{fontWeight:700}}>{o.t}</div>
                  {o.c && <div style={{fontSize:10, color:'var(--muted)'}}>Code: <b>{o.c}</b></div>}
                </div>
                <button style={{fontSize:10, fontWeight:800, color:'var(--hot)', letterSpacing:'0.06em'}}>APPLY</button>
              </div>
            ))}
          </div>

          {/* Delivery */}
          <div style={{marginTop:16, background:'#fff', border:'1px solid var(--line)', borderRadius:14, padding:16}}>
            <div style={{display:'flex', alignItems:'center', gap:10, marginBottom:10}}>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M 2 12 L 22 12 M 2 12 L 2 18 Q 2 20 4 20 L 16 20 M 16 20 Q 16 17 19 17 Q 22 17 22 20 L 22 18 L 22 14 L 17 14 L 14 12"/></svg>
              <span style={{fontSize:13, fontWeight:800}}>Delivery & Returns</span>
            </div>
            <div style={{display:'flex', gap:8, marginBottom:10}}>
              <input placeholder="Enter PIN code" style={{flex:1, border:'1px solid var(--line)', padding:'8px 12px', borderRadius:8, fontSize:12, outline:'none'}}/>
              <button style={{background:'var(--ink)', color:'#fff', padding:'8px 14px', borderRadius:8, fontSize:11, fontWeight:800}}>CHECK</button>
            </div>
            <div style={{fontSize:11, color:'var(--muted)', lineHeight:1.6}}>
              ✅ Ships within 24 hrs<br/>
              ✅ COD available<br/>
              ✅ 7-day easy returns
            </div>
          </div>
        </aside>
      </section>

      {/* Complete the look */}
      <section style={{background:'var(--ink)', color:'#fff', padding:'40px 40px 48px', marginTop:40}}>
        <div style={{maxWidth:1440, margin:'0 auto'}}>
          <div style={{display:'flex', alignItems:'end', justifyContent:'space-between', marginBottom:20}}>
            <div>
              <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--butter)'}}>💘 COMPLETE THE LOOK</div>
              <h2 style={{fontFamily:'var(--font-display)', fontSize:36, fontWeight:900, letterSpacing:'-0.03em', margin:'6px 0 0'}}>Save <span style={{color:'var(--butter)'}}>₹240</span> when you bundle</h2>
            </div>
            <button style={{background:'var(--hot)', color:'#fff', padding:'14px 22px', borderRadius:999, fontWeight:900, fontSize:12, letterSpacing:'0.05em', textTransform:'uppercase'}}>Add bundle · ₹899</button>
          </div>
          <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr) 60px repeat(1, 1fr)', gap:12, alignItems:'center'}}>
            {[PRODUCTS[0], PRODUCTS[5], PRODUCTS[13]].map((p,i)=>(
              <React.Fragment key={p.id}>
                <div style={{background:'#fff', borderRadius:'var(--r-md)', overflow:'hidden', color:'var(--ink)'}}>
                  <div style={{aspectRatio:'1/1'}}><ProductArt product={p}/></div>
                  <div style={{padding:'10px 12px'}}>
                    <div style={{fontSize:12, fontWeight:700}}>{p.name}</div>
                    <div style={{fontSize:12, fontWeight:900, color:'var(--hot)', marginTop:2}}>₹{p.price}</div>
                  </div>
                </div>
                {i < 2 && <div style={{fontSize:28, fontWeight:900, textAlign:'center', color:'var(--butter)'}}>+</div>}
              </React.Fragment>
            ))}
            <div style={{fontSize:28, fontWeight:900, textAlign:'center', color:'var(--butter)'}}>=</div>
            <div style={{background:'var(--butter)', color:'var(--ink)', borderRadius:'var(--r-md)', padding:20, textAlign:'center'}}>
              <div style={{fontSize:10, fontWeight:800, letterSpacing:'0.08em'}}>BUNDLE PRICE</div>
              <div style={{fontFamily:'var(--font-display)', fontSize:32, fontWeight:900, letterSpacing:'-0.02em', margin:'4px 0'}}>₹899</div>
              <div style={{fontSize:11, textDecoration:'line-through', opacity:0.5}}>₹1139</div>
              <div style={{fontSize:11, fontWeight:800, marginTop:4}}>SAVE ₹240</div>
            </div>
          </div>
        </div>
      </section>

      {/* Details / Description */}
      <section style={{maxWidth:1440, margin:'56px auto 0', padding:'0 40px', display:'grid', gridTemplateColumns:'1fr 1fr', gap:48}}>
        <div>
          <div style={{fontSize:11, fontWeight:800, letterSpacing:'0.1em', color:'var(--hot)', marginBottom:6}}>THE DETAILS</div>
          <h2 style={{fontFamily:'var(--font-display)', fontSize:32, fontWeight:900, letterSpacing:'-0.03em', margin:'0 0 16px'}}>Lil' hearts,<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> big main-character energy.</span></h2>
          <p style={{fontSize:14, lineHeight:1.6, color:'var(--ink)', opacity:0.8}}>The hugger you'll never take off. Snug-fit closure, butter-gold plating that actually lasts, and a heart so tiny it's practically illegal. Pair with your other studs for a cluttered-ear moment or let them solo.</p>
          <ul style={{fontSize:13, lineHeight:1.9, marginTop:12, paddingLeft:20}}>
            <li>18K gold plated over brass</li>
            <li>Hypoallergenic, lead-free, nickel-free</li>
            <li>Diameter: 10mm · Weight: 1.2g per pair</li>
            <li>6-month tarnish-free guarantee</li>
          </ul>
        </div>
        <div style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-lg)', padding:24}}>
          <div style={{display:'flex', alignItems:'center', justifyContent:'space-between', marginBottom:16}}>
            <div>
              <div style={{fontFamily:'var(--font-display)', fontSize:48, fontWeight:900, lineHeight:1}}>4.8</div>
              <Stars value={4.8} size={14}/>
              <div style={{fontSize:11, color:'var(--muted)', marginTop:4}}>Based on 1,284 reviews</div>
            </div>
            <button style={{background:'var(--ink)', color:'#fff', padding:'12px 18px', borderRadius:999, fontWeight:800, fontSize:12, letterSpacing:'0.05em', textTransform:'uppercase'}}>Write a review</button>
          </div>
          {[5,4,3,2,1].map(r=>(
            <div key={r} style={{display:'flex', alignItems:'center', gap:10, fontSize:12, marginBottom:6}}>
              <span style={{width:20, fontWeight:700}}>{r}★</span>
              <div style={{flex:1, height:6, background:'var(--line)', borderRadius:999}}>
                <div style={{width:`${[88,9,2,0.5,0.5][5-r]}%`, height:'100%', background: r>=4 ? 'var(--success)' : 'var(--hot)', borderRadius:999}}/>
              </div>
              <span style={{width:40, textAlign:'right', color:'var(--muted)'}}>{[88,9,2,0.5,0.5][5-r]}%</span>
            </div>
          ))}
        </div>
      </section>

      {/* Recent reviews */}
      <section style={{maxWidth:1440, margin:'32px auto 0', padding:'0 40px'}}>
        <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr)', gap:14}}>
          {REVIEWS.slice(0,3).map((r,i)=>(
            <div key={i} style={{background:'#fff', border:'1px solid var(--line)', borderRadius:'var(--r-md)', padding:18}}>
              <div style={{display:'flex', alignItems:'center', gap:10, marginBottom:10}}>
                <div style={{width:36, height:36, borderRadius:999, background:r.color, display:'grid', placeItems:'center', fontWeight:900, color:'var(--ink)'}}>{r.avatar}</div>
                <div style={{flex:1}}>
                  <div style={{fontSize:12, fontWeight:800}}>{r.name}</div>
                  <Stars value={r.rating} size={10}/>
                </div>
              </div>
              <p style={{fontSize:12, lineHeight:1.5, margin:'0 0 10px'}}>{r.text}</p>
              <div style={{display:'flex', gap:6}}>
                {[1,2,3].map(v=>(
                  <div key={v} style={{width:48, height:48, borderRadius:8, overflow:'hidden'}}><LifestyleArt variant={v}/></div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* You may also like */}
      <section style={{maxWidth:1440, margin:'64px auto 0', padding:'0 40px'}}>
        <h2 style={{fontFamily:'var(--font-display)', fontSize:36, fontWeight:900, letterSpacing:'-0.03em', marginBottom:20}}>You may also<span style={{fontFamily:'var(--font-serif)', fontStyle:'italic', fontWeight:400}}> obsess over.</span></h2>
        <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', gap:16}}>
          {related.map(p=><ProductCard key={p.id} product={p}/>)}
        </div>
      </section>

      <div style={{marginTop:80}}/>
      <SiteFooter/>

      {/* Sticky mobile buy bar sim (desktop preview at bottom of page) */}
      <div style={{position:'sticky', bottom:0, background:'#fff', borderTop:'1px solid var(--line)', padding:'12px 40px', display:'flex', alignItems:'center', gap:16, boxShadow:'0 -8px 24px rgba(0,0,0,0.06)'}}>
        <div style={{width:48, height:48, borderRadius:10, overflow:'hidden'}}><ProductArt product={product} size={48}/></div>
        <div style={{flex:1}}>
          <div style={{fontSize:13, fontWeight:800}}>{product.name}</div>
          <div style={{display:'flex', gap:6, alignItems:'baseline'}}>
            <span style={{fontSize:16, fontWeight:900, fontFamily:'var(--font-display)'}}>₹299</span>
            <span style={{fontSize:12, textDecoration:'line-through', color:'var(--muted)'}}>₹599</span>
            <span style={{fontSize:11, fontWeight:800, color:'var(--hot)'}}>50% OFF</span>
          </div>
        </div>
        <button style={{background:'var(--ink)', color:'#fff', padding:'12px 20px', borderRadius:999, fontWeight:800, fontSize:12, letterSpacing:'0.05em', textTransform:'uppercase'}}>Add to cart</button>
        <button style={{background:'var(--hot)', color:'#fff', padding:'12px 20px', borderRadius:999, fontWeight:800, fontSize:12, letterSpacing:'0.05em', textTransform:'uppercase'}}>⚡ Buy now</button>
      </div>
    </div>
  );
}

window.PDPPage = PDPPage;
