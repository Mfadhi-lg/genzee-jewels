// Direction B — Modern Sticker
const { navItems: stNav, orders: stOrders, downloads: stDownloads, addresses: stAddresses, wishlist: stWishlist, user: stUser } = window.GenzeeData;

function StShell({ active, children, title, eyebrow, accent }) {
  const I = window.Ic;
  return (
    <div className="st">
      <div className="promo">
        <span>★ NEW DROP FRIDAY · CHARM EDIT</span><span className="dot"/>
        <span>FIRST15 = 15% off ur first order</span><span className="dot"/>
        <span>Free shipping ₹499+</span><span className="dot"/>
        <span>7-day returns</span>
      </div>
      <div className="header">
        <div className="wordmark">Genzee<span className="pink">Jewels</span></div>
        <nav className="nav-top">
          <a>SHOP</a><a>NEW IN</a><a>CHARMS</a><a>SETS</a><a>SALE</a>
        </nav>
        <div className="header-right">
          <div className="search-box"><I.Search size={14}/> Search jewellery…</div>
          <button className="icon-btn"><I.Heart size={16}/></button>
          <button className="icon-btn"><I.Bag size={16}/><span className="badge">2</span></button>
        </div>
      </div>

      <div className="layout">
        <aside className="sidebar">
          <div className="greet-block">
            <div className="sticker">★ PEARL</div>
            <div className="hi">Hi babe</div>
            <div className="who">Nisha B.</div>
            <div className="meta">MEMBER · 1280 PTS</div>
          </div>
          <div className="nav-section-label">// MY ACCOUNT</div>
          {stNav.map(item => {
            const Icon = I[item.icon];
            return (
              <a key={item.id} className={`snav-item${active === item.id ? ' active' : ''}`}>
                <span className="ic"><Icon size={16}/></span>
                {item.label}
                {item.count ? <span className="num">{String(item.count).padStart(2,'0')}</span> : null}
              </a>
            );
          })}
          <a className="logout"><I.Logout size={16}/> Log out</a>
        </aside>

        <main className="main">
          {eyebrow ? <div className="page-eyebrow">{eyebrow}</div> : null}
          <h1 className="page-h1">{title}</h1>
          {children}
        </main>
      </div>
    </div>
  );
}

function StDashboard() {
  return (
    <StShell active="dashboard" title={<>Hi <em>Nisha</em>. ✿</>} eyebrow="// DASHBOARD · 27 APR 2026">
      <div className="grid-row row-12">
        <div className="tracker-card">
          <div className="order-id">ORDER #GZ-24891</div>
          <div className="pname">Pearl Baby Lariat</div>
          <div className="arrival">Arriving <b>Wed, Apr 29</b> via DTDC</div>
          <div className="stops">
            <div className="stop done"><div className="lab">Confirmed</div><div className="bar"></div></div>
            <div className="stop done"><div className="lab">Packed</div><div className="bar"></div></div>
            <div className="stop active"><div className="lab">In transit</div><div className="bar"></div></div>
            <div className="stop"><div className="lab">Delivered</div><div className="bar"></div></div>
          </div>
        </div>
        <div className="loyalty-card">
          <div className="lbl">// LOYALTY · PEARL</div>
          <div className="pts">1,280</div>
          <div className="next">720 pts to <b>DIAMOND</b><br/>Free engraving + early Thursday drops</div>
          <div className="bar"><div></div></div>
        </div>
      </div>

      <div className="grid-row row-1212">
        <div className="card qstat" style={{background: 'var(--magenta-soft)'}}>
          <div className="l">Total orders</div>
          <div className="v">12</div>
        </div>
        <div className="card qstat">
          <div className="l">Spent in 2026</div>
          <div className="v">₹18,420</div>
        </div>
        <div className="card qstat dark">
          <div className="l" style={{color:'rgba(250,246,239,0.6)'}}>Wishlist saves</div>
          <div className="v" style={{color: 'var(--cream)'}}>07</div>
        </div>
      </div>

      <h2 style={{fontFamily:'Fraunces', fontWeight:500, fontSize: 28, margin: '40px 0 16px', letterSpacing:'-0.02em'}}>Recent orders</h2>
      <div className="card table-card">
        <div className="table-head">
          <div></div><div>Item</div><div>Date</div><div>Total</div><div>Status</div><div></div>
        </div>
        {stOrders.slice(0,3).map(o => (
          <div className="torder" key={o.id}>
            <div className="tthumb"></div>
            <div>
              <div className="tname">{o.name}</div>
              <div className="tsub">{o.id}</div>
            </div>
            <div className="tsub" style={{margin:0}}>{o.date}</div>
            <div className="tname" style={{fontSize: 15}}>{o.total}</div>
            <div><span className={`spill ${o.status}`}>{o.status}</span></div>
            <button className="tcta">Track →</button>
          </div>
        ))}
      </div>

      <h2 style={{fontFamily:'Fraunces', fontWeight:500, fontSize: 28, margin: '40px 0 16px', letterSpacing:'-0.02em'}}>From your wishlist <span style={{color:'var(--magenta)'}}>♥</span></h2>
      <div className="wish-strip">
        {stWishlist.map((w,i) => (
          <div key={i} className="swish">
            <div className="heart"><window.Ic.Heart size={14}/></div>
            <div className="img"></div>
            <div className="body">
              <div className="ttl">{w.name}</div>
              <div className="pr">{w.price}</div>
            </div>
          </div>
        ))}
      </div>

      <h2 style={{fontFamily:'Fraunces', fontWeight:500, fontSize: 28, margin: '40px 0 16px', letterSpacing:'-0.02em'}}>Saved addresses</h2>
      <div className="addr-sgrid">
        {stAddresses.slice(0,2).map((a,i) => (
          <div key={i} className={`saddr${a.default ? ' def' : ''}`}>
            <span className="tag">{a.default ? '★ DEFAULT' : `// ${a.tag.toUpperCase()}`}</span>
            <h4>{a.name}</h4>
            <div className="ln">{a.lines.map((l,j) => <div key={j}>{l}</div>)}</div>
            <div className="acts">
              <button className="a-btn">EDIT</button>
              {!a.default && <button className="a-btn">SET DEFAULT</button>}
            </div>
          </div>
        ))}
        <div className="saddr add">
          <window.Ic.Plus size={28}/>
          <div style={{fontFamily:'Fraunces', fontWeight:500, fontSize:18, marginTop: 10}}>Add address</div>
          <div className="ln" style={{marginTop:4, fontSize:11}}>For gifts &amp; surprises</div>
        </div>
      </div>
    </StShell>
  );
}

function StOrders() {
  return (
    <StShell active="orders" title={<>All <em>orders</em>.</>} eyebrow="// 12 ITEMS · LIFETIME">
      <div className="grid-row row-1212">
        <div className="card qstat" style={{background: 'var(--magenta-soft)'}}>
          <div className="l">Total orders</div>
          <div className="v">12</div>
        </div>
        <div className="card qstat">
          <div className="l">Lifetime spend</div>
          <div className="v">₹47,890</div>
        </div>
        <div className="card qstat dark">
          <div className="l" style={{color:'rgba(250,246,239,0.6)'}}>In transit</div>
          <div className="v" style={{color: 'var(--cream)'}}>01</div>
        </div>
      </div>

      <div className="card table-card" style={{marginTop: 20}}>
        <div className="table-head">
          <div></div><div>Item</div><div>Date</div><div>Total</div><div>Status</div><div></div>
        </div>
        {stOrders.map(o => (
          <div className="torder" key={o.id}>
            <div className="tthumb"></div>
            <div>
              <div className="tname">{o.name}</div>
              <div className="tsub">{o.id} · {o.qty} item{o.qty>1?'s':''}</div>
            </div>
            <div className="tsub" style={{margin:0}}>{o.date}</div>
            <div className="tname" style={{fontSize: 15}}>{o.total}</div>
            <div><span className={`spill ${o.status}`}>{o.status}</span></div>
            <button className="tcta">View →</button>
          </div>
        ))}
      </div>
    </StShell>
  );
}

function StDownloads() {
  return (
    <StShell active="downloads" title={<>Your <em>downloads</em>.</>} eyebrow="// CARE GUIDES · CERTIFICATES · ARTWORK">
      <div className="dl-grid" style={{marginTop: 24}}>
        {stDownloads.map((d,i) => (
          <div key={i} className="dl-card">
            <div className="file-ic">.{d.ext}</div>
            <div className="info">
              <div className="nm">{d.name}</div>
              <div className="mt">From {d.order} · expires {d.expires} · {d.remaining} left</div>
              <div className="row-bottom">
                <span className="spill processing">{d.ext}</span>
                <button className="tcta">Download ↓</button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </StShell>
  );
}

function StAddresses() {
  return (
    <StShell active="addresses" title={<>Where to <em>send love</em>?</>} eyebrow="// 3 ADDRESSES SAVED">
      <div className="addr-sgrid" style={{marginTop: 24}}>
        {stAddresses.map((a,i) => (
          <div key={i} className={`saddr${a.default ? ' def' : ''}`}>
            <span className="tag">{a.default ? '★ DEFAULT · SHIPPING' : `// ${a.tag.toUpperCase()}`}</span>
            <h4>{a.name}</h4>
            <div className="ln">{a.lines.map((l,j) => <div key={j}>{l}</div>)}</div>
            <div className="acts">
              <button className="a-btn">EDIT</button>
              {!a.default && <button className="a-btn">SET DEFAULT</button>}
              <button className="a-btn">DELETE</button>
            </div>
          </div>
        ))}
        <div className="saddr add">
          <window.Ic.Plus size={28}/>
          <div style={{fontFamily:'Fraunces', fontWeight:500, fontSize:18, marginTop: 10}}>Add address</div>
          <div className="ln" style={{marginTop:4, fontSize:11}}>For gifts &amp; surprises</div>
        </div>
      </div>
    </StShell>
  );
}

function StAccount() {
  return (
    <StShell active="account" title={<>Account <em>details</em>.</>} eyebrow="// THE LITTLE THINGS THAT MAKE YOU, YOU">

      <div className="card" style={{marginTop: 24, padding: 28}}>
        <div className="corner-tag">PERSONAL</div>
        <h3 style={{marginTop: 0}}>Who are you?</h3>
        <div className="form-grid" style={{marginTop: 20}}>
          <div className="field"><span className="lab">FIRST NAME *</span><input defaultValue={stUser.first}/></div>
          <div className="field"><span className="lab">LAST NAME *</span><input defaultValue={stUser.last}/></div>
          <div className="field"><span className="lab">DISPLAY NAME *</span><input defaultValue={stUser.display}/><span className="help">// SHOWS IN REVIEWS &amp; ACCOUNT</span></div>
          <div className="field"><span className="lab">PHONE</span><input defaultValue={stUser.phone}/></div>
        </div>
      </div>

      <div className="card" style={{marginTop: 16, padding: 28}}>
        <div className="corner-tag" style={{background:'var(--ink)'}}>LOG IN</div>
        <h3 style={{marginTop: 0}}>Email &amp; password</h3>
        <div className="form-grid" style={{marginTop: 20}}>
          <div className="field" style={{gridColumn: '1 / -1'}}><span className="lab">EMAIL ADDRESS *</span><input defaultValue={stUser.email}/></div>
          <div className="field"><span className="lab">CURRENT PASSWORD</span><input type="password" defaultValue="••••••••••" /></div>
          <div className="field"><span className="lab">NEW PASSWORD</span><input type="password" placeholder="Leave blank to keep current" /></div>
          <div className="field" style={{gridColumn: '1 / -1'}}><span className="lab">CONFIRM NEW PASSWORD</span><input type="password" placeholder="Repeat new password" /></div>
        </div>
        <div className="save-row">
          <button className="btn-save">★ SAVE CHANGES</button>
          <button className="btn-cancel">CANCEL</button>
        </div>
      </div>

      <div className="card pink" style={{marginTop: 16, padding: 28}}>
        <div className="corner-tag" style={{background:'var(--ink)'}}>VIBES</div>
        <h3 style={{marginTop: 0}}>Preferences &amp; gifting</h3>
        <div className="form-grid" style={{marginTop: 20}}>
          <div className="field"><span className="lab">DEFAULT GIFT BOX</span><select defaultValue="pink"><option value="pink">Signature pink box · free</option><option>Plain kraft</option><option>None</option></select></div>
          <div className="field"><span className="lab">NEWSLETTER</span><select defaultValue="weekly"><option>Daily drops</option><option value="weekly">Weekly digest</option><option>Only big launches</option><option>Unsubscribe</option></select></div>
        </div>
      </div>

    </StShell>
  );
}

window.St = { StDashboard, StOrders, StDownloads, StAddresses, StAccount };
