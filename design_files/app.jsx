// Mount both directions inside the design canvas
const { DesignCanvas, DCSection, DCArtboard } = window;

function App() {
  const { Ed, St } = window;
  return (
    <DesignCanvas title="Genzee Jewels — My Account redesign" subtitle="Two directions × 5 WooCommerce screens. Drag to pan · scroll to zoom · click any artboard to focus.">

      <DCSection id="dir-a" title="Direction A — Editorial Boutique" subtitle="Serif-led, generous whitespace, magazine-style cards, magenta as a quiet accent.">
        <DCArtboard id="a-dashboard"  label="Dashboard"       width={1280} height={2200}><Ed.EdDashboard/></DCArtboard>
        <DCArtboard id="a-orders"     label="Orders"          width={1280} height={1100}><Ed.EdOrders/></DCArtboard>
        <DCArtboard id="a-downloads"  label="Downloads"       width={1280} height={900}><Ed.EdDownloads/></DCArtboard>
        <DCArtboard id="a-addresses"  label="Addresses"       width={1280} height={1000}><Ed.EdAddresses/></DCArtboard>
        <DCArtboard id="a-account"    label="Account details" width={1280} height={1700}><Ed.EdAccount/></DCArtboard>
      </DCSection>

      <DCSection id="dir-b" title="Direction B — Modern Sticker" subtitle="Sharp 2px borders, sticker tags, mono microtype, magenta as structure. Gen-Z first.">
        <DCArtboard id="b-dashboard"  label="Dashboard"       width={1280} height={2200}><St.StDashboard/></DCArtboard>
        <DCArtboard id="b-orders"     label="Orders"          width={1280} height={1200}><St.StOrders/></DCArtboard>
        <DCArtboard id="b-downloads"  label="Downloads"       width={1280} height={900}><St.StDownloads/></DCArtboard>
        <DCArtboard id="b-addresses"  label="Addresses"       width={1280} height={1000}><St.StAddresses/></DCArtboard>
        <DCArtboard id="b-account"    label="Account details" width={1280} height={1700}><St.StAccount/></DCArtboard>
      </DCSection>

    </DesignCanvas>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<App/>);
