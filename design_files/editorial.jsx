// Direction A — Editorial Boutique
const { Ic: EdIc } = window;
const { navItems: edNav, orders: edOrders, downloads: edDownloads, addresses: edAddresses, wishlist: edWishlist, user: edUser } = window.GenzeeData;

function EdShell({ active, children, title, sub }) {
  const I = window.Ic;
  return (
    <div className="ed">
      <div className="promo">
        <span>✶ Free shipping on orders ₹499+</span><span className="dot" />
        <span>Use code FIRST15 for 15% off your first order</span><span className="dot" />
        <span>COD available</span><span className="dot" />
        <span>New drops every Friday!</span><span className="dot" />
        <span>7-day hassle-free returns</span>
      </div>
      <div className="header">
        <div className="nav-left">
          <a>Shop</a><a>New In</a><a>Collections</a><a>Story</a>
        </div>
        <div className="wordmark">Genzee<span className="pink">Jewels</span></div>
        <div className="nav-right">
          <div className="search"><I.Search size={14} /> Search jewellery…</div>
          <button className="icon-btn"><I.Heart size={16} /></button>
          <button className="icon-btn bag-dot"><I.Bag size={16} /></button>
        </div>
      </div>

      <div className="layout">
        <aside className="sidebar">
          <div className="side-greeting">
            <div className="label">Welcome back</div>
            <div className="name">Nisha,<br/>jewel keeper.</div>
            <div className="tier"><span className="gem"></span> Pearl member · 1,280 pts</div>
          </div>
          <nav className="nav">
            {edNav.map(item => {
              const Icon = I[item.icon];
              return (
                <a key={item.id} className={`nav-item${active === item.id ? ' active' : ''}`}>
                  <span className="ic"><Icon size={16}/></span>
                  {item.label}
                  {item.count ? <span className="badge">{item.count}</span> : null}
                </a>
              );
            })}
            <div className="nav-divider"></div>
            <a className="nav-item"><span className="ic"><I.Logout size={16}/></span> Log out</a>
          </nav>
          <div className="care-card">
            <div className="eyebrow">Tip · 02</div>
            <h4>Keep the shine in your sterling silver.</h4>
            <p>Store pieces individually, away from sunlight. Polish gently with a soft cloth.</p>
            <a>Read care guide →</a>
          </div>
        </aside>

        <main className="main">
          <div className="crumbs">My Account <span className="sep">/</span> {title}</div>
          <h1 className="page-title">{title}</h1>
          {sub ? <p className="page-sub">{sub}</p> : null}
          {children}
        </main>
      </div>
    </div>
  );
}

function EdDashboard() {
  const I = window.Ic;
  return (
    <EdShell active="dashboard" title="Dashboard" sub="A quiet little corner for your orders, points, and saved pieces.">
      <div className="hero-track">
        <div className="ht-left">
          <div className="ht-eyebrow">Active order · #GZ-24891</div>
          <h2 className="ht-title">Your Pearl Baby Lariat is on its way</h2>
          <div className="ht-meta">Arriving by Wednesday, Apr 29 · DTDC tracking 7821 4490</div>
          <div className="ht-steps">
            <div className="ht-step"><div className="bar done"></div><div className="lbl">Confirmed</div></div>
            <div className="ht-step"><div className="bar done"></div><div className="lbl">Packed</div></div>
            <div className="ht-step active"><div className="bar current" style={{background: '#E91E63'}}></div><div className="lbl">In transit</div></div>
            <div className="ht-step"><div className="bar"></div><div className="lbl">Delivered</div></div>
          </div>
          <div className="ht-cta">
            <button className="btn btn-primary">Track shipment</button>
            <button className="btn btn-ghost-light">Order details</button>
          </div>
        </div>
        <div className="ht-right">
          <div className="stamp">In transit</div>
          <div className="product-frame">[ product shot ]</div>
        </div>
      </div>

      <div className="stats-row">
        <div className="stat"><div className="lbl">Orders</div><div className="val">12</div><div className="delta">+2 this month</div></div>
        <div className="stat"><div className="lbl">Spent in 2026</div><div className="val">₹18,420</div><div className="delta">+₹2,499</div></div>
        <div className="stat"><div className="lbl">Loyalty points</div><div className="val">1,280</div><div className="delta">720 to Diamond</div></div>
        <div className="stat"><div className="lbl">Wishlist</div><div className="val">7</div><div className="delta neg">2 low in stock</div></div>
      </div>

      <div className="sec-head">
        <h2>Recent orders</h2>
        <a className="link">View all →</a>
      </div>
      <div className="order-list">
        {edOrders.slice(0,3).map(o => (
          <div className="order-row" key={o.id}>
            <div className="thumb"></div>
            <div>
              <div className="o-name">{o.name}</div>
              <div className="o-sub">{o.id}</div>
            </div>
            <div><div className="o-meta-lbl">Placed</div><div className="o-meta-val">{o.date}</div></div>
            <div><div className="o-meta-lbl">Total</div><div className="o-meta-val">{o.total}</div></div>
            <div><span className={`pill ${o.status}`}><span className="dot"></span>{o.eta}</span></div>
            <button className="row-cta">View →</button>
          </div>
        ))}
      </div>

      <div className="two-col">
        <div className="panel">
          <h3>Saved addresses</h3>
          <div className="sub">Pieces ship faster when we know exactly where you are.</div>
          {edAddresses.slice(0,2).map((a,i) => (
            <div key={i} className={`addr-card${a.default ? ' default' : ''}`}>
              {a.default && <div className="addr-tag">Default</div>}
              <div className="who">{a.tag} · {a.name}</div>
              <div className="lines">{a.lines.map((l,j) => <div key={j}>{l}</div>)}</div>
              <div className="actions"><a>Edit</a><a>Use as default</a></div>
            </div>
          ))}
        </div>
        <div className="panel">
          <h3>From your wishlist</h3>
          <div className="sub">Quietly waiting in your dreams folder.</div>
          <div className="wish-grid">
            {edWishlist.map((w,i) => (
              <div key={i} className="wish">
                <div className="img"></div>
                <div className="body">
                  <div className="title">{w.name}</div>
                  <div className="price">{w.price}</div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="loyalty">
        <div>
          <div className="eyebrow">Genzee Circle</div>
          <h3>You&rsquo;re 720 points away from Diamond.</h3>
          <p>Diamond unlocks free engraving, early drops on Thursdays, and a birthday gift on us.</p>
          <div className="progress"><div></div></div>
        </div>
        <div className="points-display">
          <div className="points-num">1,280</div>
          <div className="points-lbl">points · Pearl tier</div>
        </div>
      </div>
    </EdShell>
  );
}

function EdOrders() {
  return (
    <EdShell active="orders" title="Orders" sub="Every piece you&rsquo;ve given a home, in one place.">
      <div className="sec-head" style={{marginTop: 20}}>
        <h2>All orders <span style={{color:'var(--muted)', fontSize: 14, fontFamily:'JetBrains Mono'}}>· 12</span></h2>
        <a className="link">Filter &amp; sort</a>
      </div>
      <div className="order-list">
        {edOrders.map(o => (
          <div className="order-row" key={o.id}>
            <div className="thumb"></div>
            <div>
              <div className="o-name">{o.name}</div>
              <div className="o-sub">{o.id} · {o.qty} item{o.qty>1?'s':''}</div>
            </div>
            <div><div className="o-meta-lbl">Placed</div><div className="o-meta-val">{o.date}</div></div>
            <div><div className="o-meta-lbl">Total</div><div className="o-meta-val">{o.total}</div></div>
            <div><span className={`pill ${o.status}`}><span className="dot"></span>{o.eta}</span></div>
            <button className="row-cta">View →</button>
          </div>
        ))}
      </div>
    </EdShell>
  );
}

function EdDownloads() {
  return (
    <EdShell active="downloads" title="Downloads" sub="Care guides, certificates, and custom artwork — all yours to keep.">
      <div className="order-list" style={{marginTop: 32}}>
        {edDownloads.map((d, i) => (
          <div className="dl-row" key={i}>
            <div className="dl-icon">{d.ext}</div>
            <div>
              <div className="dl-name">{d.name}</div>
              <div className="dl-sub">From order {d.order}</div>
            </div>
            <div><div className="o-meta-lbl">Type</div><div className="o-meta-val">{d.ext} document</div></div>
            <div><div className="o-meta-lbl">Expires</div><div className="o-meta-val">{d.expires}</div></div>
            <div><div className="o-meta-lbl">Downloads left</div><div className="o-meta-val">{d.remaining}</div></div>
            <button className="download-btn">Download</button>
          </div>
        ))}
      </div>
    </EdShell>
  );
}

function EdAddresses() {
  return (
    <EdShell active="addresses" title="Addresses" sub="Where should your sparkle land?">
      <div className="addr-grid">
        {edAddresses.map((a,i) => (
          <div key={i} className={`addr-large${a.default ? ' default' : ''}`}>
            <div className="icon-row">
              <span className={`lab${a.default ? ' def' : ''}`}>{a.default ? 'Default · Shipping' : a.tag}</span>
              <span style={{opacity: .6}}><window.Ic.Pin size={16}/></span>
            </div>
            <h3>{a.tag}</h3>
            <div className="lines">
              <div style={{fontWeight: 600, marginBottom: 4}}>{a.name}</div>
              {a.lines.map((l,j) => <div key={j}>{l}</div>)}
            </div>
            <div className="acts">
              <button className="row-cta" style={a.default ? {borderColor: 'rgba(250,246,239,0.3)', color: 'var(--cream)'} : {}}>Edit</button>
              {!a.default && <button className="row-cta">Set as default</button>}
            </div>
          </div>
        ))}
        <div className="add-new">
          <div className="plus"><window.Ic.Plus size={20}/></div>
          <div className="ttl">Add a new address</div>
          <div className="sub">For surprise gifts, side-trips, or a new home</div>
        </div>
      </div>
    </EdShell>
  );
}

function EdAccount() {
  return (
    <EdShell active="account" title="Account details" sub="The little things that make you, you. We&rsquo;ll only use them to send beautiful things your way.">
      <div className="panel" style={{marginTop: 32}}>
        <h3>Personal information</h3>
        <div className="sub">Update your name and how you appear in reviews.</div>
        <div className="form-grid">
          <div className="field"><span className="lab">First name *</span><input defaultValue={edUser.first}/></div>
          <div className="field"><span className="lab">Last name *</span><input defaultValue={edUser.last}/></div>
          <div className="field"><span className="lab">Display name *</span><input defaultValue={edUser.display}/><span className="help">This is how your name will appear in reviews and the account section.</span></div>
          <div className="field"><span className="lab">Phone</span><input defaultValue={edUser.phone}/></div>
        </div>
      </div>

      <div className="panel" style={{marginTop: 20}}>
        <h3>Email &amp; password</h3>
        <div className="sub">Used to log in and to send your order updates.</div>
        <div className="form-grid">
          <div className="field" style={{gridColumn: '1 / -1'}}><span className="lab">Email address *</span><input defaultValue={edUser.email}/></div>
          <div className="field"><span className="lab">Current password</span><input type="password" defaultValue="••••••••••" /></div>
          <div className="field"><span className="lab">New password</span><input type="password" placeholder="Leave blank to keep current" /></div>
          <div className="field" style={{gridColumn: '1 / -1'}}><span className="lab">Confirm new password</span><input type="password" placeholder="Repeat new password" /></div>
        </div>
        <div className="save-row">
          <a className="cancel">Cancel changes</a>
          <button className="btn-save">Save changes</button>
        </div>
      </div>

      <div className="panel" style={{marginTop: 20}}>
        <h3>Preferences</h3>
        <div className="sub">Quiet hours, gift packaging, the lot.</div>
        <div className="form-grid">
          <div className="field"><span className="lab">Default gift packaging</span><select defaultValue="pink"><option value="pink">Signature pink box (free)</option><option>Plain kraft</option><option>None</option></select></div>
          <div className="field"><span className="lab">Newsletter</span><select defaultValue="weekly"><option>Daily drops</option><option value="weekly">Weekly digest</option><option>Only big launches</option><option>Unsubscribe</option></select></div>
        </div>
      </div>
    </EdShell>
  );
}

window.Ed = { EdDashboard, EdOrders, EdDownloads, EdAddresses, EdAccount };
