/* =============================================================
   Genzee Jewels — main.js
   Phase 2: Mobile nav toggle with hamburger → X animation
   and aria-label update. Cart count AJAX update hook for
   WooCommerce added later in Phase 4.
   ============================================================= */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		initMobileNav();
		initCountdowns();
		initLiveToast();
	} );

	function initMobileNav() {
		var toggle = document.querySelector( '.site-header__menu-toggle' );
		var nav    = document.getElementById( 'primary-navigation' );

		if ( ! toggle || ! nav ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var isExpanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			var willOpen   = ! isExpanded;

			toggle.setAttribute( 'aria-expanded', String( willOpen ) );
			toggle.setAttribute(
				'aria-label',
				willOpen ? 'Close navigation menu' : 'Open navigation menu'
			);
			nav.classList.toggle( 'is-open', willOpen );
		} );

		// Close nav when clicking outside header area.
		document.addEventListener( 'click', function ( event ) {
			var header = document.getElementById( 'site-header' );
			if (
				nav.classList.contains( 'is-open' ) &&
				header &&
				! header.contains( event.target )
			) {
				closeNav( toggle, nav );
			}
		} );

		// Close nav on Escape key.
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				closeNav( toggle, nav );
				toggle.focus();
			}
		} );
	}

	function closeNav( toggle, nav ) {
		nav.classList.remove( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-label', 'Open navigation menu' );
	}

	function initCountdowns() {
		var countdowns = document.querySelectorAll( '.hero-countdown[data-countdown], .iz-countdown[data-countdown]' );
		if ( ! countdowns.length ) { return; }

		countdowns.forEach( function( el ) {
			var remaining = parseInt( el.getAttribute( 'data-countdown' ), 10 ) || 0;
			var hEl = el.querySelector( '.js-countdown-h' );
			var mEl = el.querySelector( '.js-countdown-m' );
			var sEl = el.querySelector( '.js-countdown-s' );

			if ( ! hEl || ! mEl || ! sEl ) { return; }

			function pad( n ) { return String( n ).padStart( 2, '0' ); }

			function tick() {
				if ( remaining < 0 ) { remaining = 0; }
				hEl.textContent = pad( Math.floor( remaining / 3600 ) );
				mEl.textContent = pad( Math.floor( ( remaining % 3600 ) / 60 ) );
				sEl.textContent = pad( remaining % 60 );
				if ( remaining > 0 ) { remaining--; }
			}

			tick();
			setInterval( tick, 1000 );
		} );
	}

	function initLiveToast() {
		var toast = document.getElementById( 'live-toast' );
		if ( ! toast ) { return; }

		var notifications = [
			{ name: 'Priya from Mumbai',    product: 'Butter Heart Huggies', time: '2 min ago',  color: '#FFE14A' },
			{ name: 'Ananya from Delhi',    product: 'Cherry Bomb Studs',     time: '5 min ago',  color: '#FF2D6F' },
			{ name: 'Riya from Bangalore',  product: 'Pearl Baby Lariat',     time: '8 min ago',  color: '#F4EFE6' },
			{ name: 'Ishita from Pune',     product: 'Evil Eye Signet',        time: '12 min ago', color: '#C9B6FF' },
		];

		var nameEl    = toast.querySelector( '.live-toast__name' );
		var msgEl     = toast.querySelector( '.live-toast__msg' );
		var timeEl    = toast.querySelector( '.live-toast__time' );
		var iconEl    = toast.querySelector( '.live-toast__icon' );
		var closeBtn  = toast.querySelector( '.live-toast__close' );
		var idx       = 0;
		var dismissed = false;

		function show( n ) {
			nameEl.textContent = n.name;
			msgEl.textContent  = 'bought ' + n.product;
			timeEl.textContent = '✓ ' + n.time;
			iconEl.style.setProperty( '--toast-color', n.color );
			toast.classList.add( 'is-visible' );
		}

		function hide() {
			toast.classList.remove( 'is-visible' );
		}

		function cycle() {
			if ( dismissed ) { return; }
			hide();
			setTimeout( function () {
				if ( dismissed ) { return; }
				idx = ( idx + 1 ) % notifications.length;
				show( notifications[ idx ] );
			}, 400 );
		}

		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', function () {
				dismissed = true;
				hide();
				setTimeout( function () {
					toast.style.display = 'none';
				}, 400 );
			} );
		}

		/* Show first notification after 2 s, then cycle every 6 s */
		setTimeout( function () {
			if ( ! dismissed ) { show( notifications[ 0 ] ); }
		}, 2000 );

		setInterval( cycle, 6000 );
	}

} )();
