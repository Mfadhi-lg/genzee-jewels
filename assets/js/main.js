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
		initHeaderSearch();
		initWishlist();
		initDashWishlistPreview();
		initCartDrawer();
		initCartAjaxReload();
		initCartDrawerQuantitySteppers();
		initCartPageQuantitySteppers();
		initArchiveFilterDrawer();
		initArchiveGridView();
		initPdpGallery();
		initPdpBundleAdd();
		initPdpQuantitySteppers();
		initPdpSwatches();
		initCountdowns();
		initLiveToast();
		initStickyAtc();
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

	function initHeaderSearch() {
		var toggle = document.querySelector( '.js-search-toggle' );
		var panel = document.getElementById( 'header-search-panel' );
		var closeButton = panel ? panel.querySelector( '.js-search-close' ) : null;
		var input = panel ? panel.querySelector( 'input[type="search"]' ) : null;

		if ( ! toggle || ! panel ) {
			return;
		}

		function openSearch() {
			panel.hidden = false;
			toggle.setAttribute( 'aria-expanded', 'true' );
			toggle.setAttribute( 'aria-label', 'Close search' );
			window.requestAnimationFrame( function () {
				panel.classList.add( 'is-open' );
				if ( input ) {
					input.focus();
				}
			} );
		}

		function closeSearch( restoreFocus ) {
			panel.classList.remove( 'is-open' );
			panel.hidden = true;
			toggle.setAttribute( 'aria-expanded', 'false' );
			toggle.setAttribute( 'aria-label', 'Open search' );

			if ( restoreFocus ) {
				toggle.focus();
			}
		}

		toggle.addEventListener( 'click', function () {
			if ( toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				closeSearch( false );
				return;
			}

			openSearch();
		} );

		if ( closeButton ) {
			closeButton.addEventListener( 'click', function () {
				closeSearch( true );
			} );
		}

		document.addEventListener( 'click', function ( event ) {
			if (
				! panel.hidden &&
				! panel.contains( event.target ) &&
				! toggle.contains( event.target )
			) {
				closeSearch( false );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && ! panel.hidden ) {
				closeSearch( true );
			}
		} );
	}

	function initWishlist() {
		var storageKey = 'genzee_wishlist_v1';
		var countEls = document.querySelectorAll( '[data-wishlist-count]' );
		var page = document.querySelector( '[data-wishlist-page]' );
		var grid = page ? page.querySelector( '[data-wishlist-grid]' ) : null;
		var emptyState = page ? page.querySelector( '[data-wishlist-empty]' ) : null;
		var statusEl = page ? page.querySelector( '[data-wishlist-status]' ) : null;
		var clearButton = page ? page.querySelector( '[data-wishlist-clear]' ) : null;
		var wishlistConfig = window.genzeeWishlist || {};
		var wishlist = readWishlist();
		var toastEl = null;
		var toastTimer = 0;
		var lastRemovedId = 0;

		function normaliseIds( value ) {
			if ( ! Array.isArray( value ) ) {
				return [];
			}

			var seen = {};
			return value.reduce( function ( ids, item ) {
				var id = parseInt( item, 10 );
				if ( id > 0 && ! seen[ id ] ) {
					seen[ id ] = true;
					ids.push( id );
				}
				return ids;
			}, [] );
		}

		function readWishlist() {
			try {
				return normaliseIds( JSON.parse( window.localStorage.getItem( storageKey ) || '[]' ) );
			} catch ( error ) {
				try {
					window.localStorage.removeItem( storageKey );
				} catch ( removeError ) {}
				return [];
			}
		}

		function writeWishlist() {
			try {
				window.localStorage.setItem( storageKey, JSON.stringify( wishlist ) );
			} catch ( error ) {}
		}

		function hasProduct( productId ) {
			return wishlist.indexOf( productId ) !== -1;
		}

		function syncToggle( toggle ) {
			var productId = parseInt( toggle.getAttribute( 'data-product-id' ), 10 );
			var saved = productId > 0 && hasProduct( productId );
			var label = saved
				? toggle.getAttribute( 'data-label-remove' )
				: toggle.getAttribute( 'data-label-add' );
			var textEl = toggle.querySelector( '[data-wishlist-label]' );

			toggle.classList.toggle( 'is-saved', saved );
			toggle.setAttribute( 'aria-pressed', saved ? 'true' : 'false' );

			if ( label ) {
				toggle.setAttribute( 'aria-label', label );
			}

			if ( textEl ) {
				textEl.textContent = saved ? 'Saved' : 'Save';
			}
		}

		function syncButtons() {
			document.querySelectorAll( '[data-wishlist-toggle][data-product-id]' ).forEach( syncToggle );
		}

		function syncCount() {
			countEls.forEach( function ( countEl ) {
				countEl.textContent = String( wishlist.length );
				countEl.classList.toggle( 'is-empty', wishlist.length === 0 );
			} );
		}

		function syncAll() {
			wishlist = normaliseIds( wishlist );
			syncButtons();
			syncCount();
			initDashWishlistPreview();
		}

		function ensureToast() {
			if ( toastEl ) {
				return toastEl;
			}

			toastEl = document.createElement( 'div' );
			toastEl.className = 'wishlist-toast';
			toastEl.setAttribute( 'role', 'status' );
			toastEl.setAttribute( 'aria-live', 'polite' );
			toastEl.innerHTML = '<span class="wishlist-toast__message"></span><button class="wishlist-toast__undo" type="button" hidden>Undo</button>';
			document.body.appendChild( toastEl );

			toastEl.querySelector( '.wishlist-toast__undo' ).addEventListener( 'click', function () {
				if ( lastRemovedId > 0 && ! hasProduct( lastRemovedId ) ) {
					wishlist.push( lastRemovedId );
					wishlist = normaliseIds( wishlist );
					writeWishlist();
					syncAll();
					if ( page ) {
						renderWishlistPage();
					}
				}

				hideToast();
			} );

			return toastEl;
		}

		function hideToast() {
			if ( toastEl ) {
				toastEl.classList.remove( 'is-visible' );
			}
		}

		function showWishlistToast( message, canUndo ) {
			var toast = ensureToast();
			var messageEl = toast.querySelector( '.wishlist-toast__message' );
			var undoButton = toast.querySelector( '.wishlist-toast__undo' );

			window.clearTimeout( toastTimer );
			messageEl.textContent = message;
			undoButton.hidden = ! canUndo;
			toast.classList.add( 'is-visible' );

			toastTimer = window.setTimeout( hideToast, 4200 );
		}

		function setWishlistStatus( message ) {
			if ( statusEl ) {
				statusEl.textContent = message || '';
			}
		}

		function showWishlistEmpty( show ) {
			if ( emptyState ) {
				emptyState.hidden = ! show;
			}

			if ( clearButton ) {
				clearButton.hidden = show || wishlist.length === 0;
			}
		}

		function renderWishlistPage() {
			if ( ! page || ! grid ) {
				return;
			}

			wishlist = readWishlist();
			syncAll();

			if ( ! wishlist.length ) {
				grid.innerHTML = '';
				setWishlistStatus( '' );
				showWishlistEmpty( true );
				return;
			}

			if ( ! wishlistConfig.ajaxUrl || ! window.fetch ) {
				grid.innerHTML = '';
				setWishlistStatus( wishlistConfig.errorLabel || 'Saved styles could not load. Please refresh and try again.' );
				showWishlistEmpty( true );
				return;
			}

			setWishlistStatus( wishlistConfig.loadingLabel || 'Loading saved styles...' );
			showWishlistEmpty( false );

			var formData = new FormData();
			formData.append( 'action', 'genzee_wishlist_products' );
			formData.append( 'nonce', wishlistConfig.nonce || '' );
			formData.append( 'ids', JSON.stringify( wishlist ) );

			fetch( wishlistConfig.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( payload ) {
					var data = payload && payload.success && payload.data ? payload.data : {};
					var renderedIds = normaliseIds( data.ids || [] );

					grid.innerHTML = data.html || '';

					if ( ! data.unavailable ) {
						wishlist = renderedIds;
						writeWishlist();
					}

					syncAll();
					setWishlistStatus( '' );
					showWishlistEmpty( renderedIds.length === 0 );
				} )
				.catch( function () {
					grid.innerHTML = '';
					setWishlistStatus( wishlistConfig.errorLabel || 'Saved styles could not load. Please refresh and try again.' );
					showWishlistEmpty( true );
				} );
		}

		document.addEventListener( 'click', function ( event ) {
			var toggle = event.target.closest( '[data-wishlist-toggle][data-product-id]' );

			if ( toggle ) {
				var productId = parseInt( toggle.getAttribute( 'data-product-id' ), 10 );

				if ( productId <= 0 ) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();

				if ( hasProduct( productId ) ) {
					wishlist = wishlist.filter( function ( id ) {
						return id !== productId;
					} );
					lastRemovedId = productId;
					showWishlistToast( wishlistConfig.removedLabel || 'Removed from wishlist.', true );
				} else {
					wishlist.push( productId );
					lastRemovedId = 0;
					showWishlistToast( wishlistConfig.savedLabel || 'Saved to wishlist.', false );
				}

				writeWishlist();
				syncAll();

				if ( page ) {
					renderWishlistPage();
				}
			}
		} );

		if ( clearButton ) {
			clearButton.addEventListener( 'click', function () {
				wishlist = [];
				lastRemovedId = 0;
				writeWishlist();
				syncAll();
				renderWishlistPage();
				showWishlistToast( wishlistConfig.clearedLabel || 'Wishlist cleared.', false );
			} );
		}

		window.addEventListener( 'storage', function ( event ) {
			if ( event.key === storageKey ) {
				wishlist = readWishlist();
				syncAll();
				renderWishlistPage();
			}
		} );

		syncAll();
		renderWishlistPage();
	}

	function initCartDrawer() {
		var trigger  = document.querySelector( '.js-cart-toggle' );
		var drawer   = document.getElementById( 'cart-drawer' );
		var backdrop = document.getElementById( 'cart-drawer-backdrop' );
		var body     = document.body;

		if ( ! drawer ) { return; }

		function openDrawer() {
			drawer.removeAttribute( 'aria-hidden' );
			body.classList.add( 'cart-drawer-open' );
			if ( backdrop ) { backdrop.setAttribute( 'aria-hidden', 'false' ); }
			if ( trigger )  { trigger.setAttribute( 'aria-expanded', 'true' ); }
			var closeBtn = drawer.querySelector( '.js-cart-close' );
			if ( closeBtn ) { closeBtn.focus(); }
		}

		function closeDrawer() {
			body.classList.remove( 'cart-drawer-open' );
			drawer.setAttribute( 'aria-hidden', 'true' );
			if ( backdrop ) { backdrop.setAttribute( 'aria-hidden', 'true' ); }
			if ( trigger ) {
				trigger.setAttribute( 'aria-expanded', 'false' );
				trigger.focus();
			}
		}

		if ( trigger ) {
			trigger.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				if ( trigger.getAttribute( 'aria-expanded' ) === 'true' ) {
					closeDrawer();
				} else {
					openDrawer();
				}
			} );
		}

		if ( backdrop ) {
			backdrop.addEventListener( 'click', closeDrawer );
		}

		// Use delegation for close buttons inside drawer (they might be replaced by fragments)
		drawer.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '.js-cart-close' ) ) {
				closeDrawer();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && drawer.getAttribute( 'aria-hidden' ) !== 'true' ) {
				closeDrawer();
			}
		} );

		// Expose openDrawer globally or via event for other parts of the script
		window.genzeeOpenCartDrawer = openDrawer;
	}

	function initCartAjaxReload() {
		if ( ! window.jQuery ) {
			return;
		}

		var $body = window.jQuery( document.body );
		var keepDrawerOpen = false;

		$body.off( '.genzeeCartDrawer' );

		window.jQuery( document ).off( '.genzeeCartDrawerRemove' ).on( 'click.genzeeCartDrawerRemove', '#cart-drawer .remove_from_cart_button', function () {
			keepDrawerOpen = true;
		} );

		function drawerIsOpen() {
			return document.body.classList.contains( 'cart-drawer-open' );
		}

		function openDrawer() {
			if ( typeof window.genzeeOpenCartDrawer === 'function' ) {
				window.genzeeOpenCartDrawer();
			}
		}

		$body.on( 'added_to_cart.genzeeCartDrawer', function () {
			keepDrawerOpen = true;
			openDrawer();
		} );

		$body.on( 'removed_from_cart.genzeeCartDrawer', function () {
			if ( keepDrawerOpen || drawerIsOpen() ) {
				openDrawer();
			}
			keepDrawerOpen = false;
		} );

		$body.on( 'wc_fragments_refreshed.genzeeCartDrawer wc_fragments_loaded.genzeeCartDrawer', function () {
			if ( keepDrawerOpen || drawerIsOpen() ) {
				openDrawer();
			}
			initCartDrawerQuantitySteppers();
		} );
	}

	function initCartDrawerQuantitySteppers() {
		var drawer = document.getElementById( 'cart-drawer' );
		if ( ! drawer ) { return; }

		var steppers = drawer.querySelectorAll( '.cart-drawer__qty' );
		if ( ! steppers.length ) { return; }

		var config = window.genzeeCart || {};

		steppers.forEach( function ( stepper ) {
			var input = stepper.querySelector( 'input.qty' );
			var minus = stepper.querySelector( '.cart-drawer__qty-btn--minus' );
			var plus  = stepper.querySelector( '.cart-drawer__qty-btn--plus' );

			if ( ! input || ! minus || ! plus || stepper.hasAttribute( 'data-initialized' ) ) {
				return;
			}

			stepper.setAttribute( 'data-initialized', 'true' );

			function updateQty( direction ) {
				var current = parseInt( input.value, 10 ) || 0;
				var min = parseInt( input.getAttribute( 'min' ), 10 ) || 0;
				var max = parseInt( input.getAttribute( 'max' ), 10 ) || Infinity;
				var next = current + direction;

				if ( next < min ) { next = min; }
				if ( next > max ) { next = max; }

				if ( next === current ) { return; }

				input.value = next;

				// Trigger AJAX update
				var itemKey = minus.getAttribute( 'data-item-key' );
				if ( ! itemKey || ! config.ajaxUrl ) { return; }

				stepper.classList.add( 'is-loading' );

				var formData = new FormData();
				formData.append( 'action', 'genzee_update_cart_item_qty' );
				formData.append( 'nonce', config.nonce );
				formData.append( 'cart_item_key', itemKey );
				formData.append( 'quantity', next );

				window.jQuery( document.body ).trigger( 'updating_cart' );

				fetch( config.ajaxUrl, {
					method: 'POST',
					body: formData
				} )
					.then( function ( response ) { return response.json(); } )
					.then( function ( data ) {
						if ( data && data.fragments ) {
							window.jQuery( document.body ).trigger( 'removed_from_cart', [ data.fragments, data.cart_hash ] );
							window.jQuery( document.body ).trigger( 'wc_fragments_refreshed' );
						}
					} )
					.catch( function () {
						stepper.classList.remove( 'is-loading' );
					} );
			}

			minus.addEventListener( 'click', function () { updateQty( -1 ); } );
			plus.addEventListener( 'click', function () { updateQty( 1 ); } );
		} );
	}

	function initPdpGallery() {
		var galleries = document.querySelectorAll( '[data-pdp-gallery]' );
		if ( ! galleries.length ) { return; }

		galleries.forEach( function ( gallery ) {
			var mainImage = gallery.querySelector( '[data-pdp-gallery-main] img' );
			var thumbs = gallery.querySelectorAll( '[data-pdp-gallery-thumb]' );

			if ( ! mainImage || ! thumbs.length ) { return; }

			thumbs.forEach( function ( thumb ) {
				thumb.addEventListener( 'click', function () {
					var full = thumb.getAttribute( 'data-full' );
					var srcset = thumb.getAttribute( 'data-srcset' );
					var sizes = thumb.getAttribute( 'data-sizes' );
					var alt = thumb.getAttribute( 'data-alt' );

					if ( full ) {
						mainImage.setAttribute( 'src', full );
					}

					if ( srcset ) {
						mainImage.setAttribute( 'srcset', srcset );
					} else {
						mainImage.removeAttribute( 'srcset' );
					}

					if ( sizes ) {
						mainImage.setAttribute( 'sizes', sizes );
					}

					if ( alt ) {
						mainImage.setAttribute( 'alt', alt );
					}

					thumbs.forEach( function ( item ) {
						item.classList.remove( 'is-active' );
						item.setAttribute( 'aria-pressed', 'false' );
					} );

					thumb.classList.add( 'is-active' );
					thumb.setAttribute( 'aria-pressed', 'true' );
				} );
			} );
		} );
	}

	function initPdpBundleAdd() {
		var bundles = document.querySelectorAll( '[data-pdp-bundle]' );
		var config = window.genzeeCart || {};

		if ( ! bundles.length ) { return; }

		function getBundleProductIds( bundle, form ) {
			var ids = bundle.getAttribute( 'data-bundle-product-ids' ) || '';

			if ( ! ids && form ) {
				ids = Array.from( form.querySelectorAll( 'input[name="genzee_bundle_product_ids[]"]' ) )
					.map( function ( input ) { return input.value; } )
					.filter( Boolean )
					.join( ',' );
			}

			return ids;
		}

		function setLoading( button, isLoading ) {
			if ( ! button ) { return; }

			button.classList.toggle( 'is-loading', isLoading );
			button.disabled = isLoading;
			button.setAttribute( 'aria-busy', isLoading ? 'true' : 'false' );
		}

		function submitBundle( bundle, form, button ) {
			var productIds = getBundleProductIds( bundle, form );

			if ( ! config.ajaxUrl || ! window.fetch || ! productIds ) {
				if ( form ) { form.submit(); }
				return;
			}

			var formData = new FormData();
			formData.append( 'action', 'genzee_add_bundle_to_cart' );
			formData.append( 'nonce', config.nonce || '' );
			formData.append( 'product_ids', productIds );

			setLoading( button, true );

			fetch( config.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			} )
				.then( function ( response ) { return response.json(); } )
				.then( function ( payload ) {
					if ( payload && payload.fragments && window.jQuery ) {
						window.jQuery( document.body ).trigger( 'added_to_cart', [ payload.fragments, payload.cart_hash, window.jQuery( button ) ] );
						window.jQuery( document.body ).trigger( 'wc_fragments_refreshed' );
						return;
					}

					if ( payload && payload.success ) {
						if ( typeof window.genzeeOpenCartDrawer === 'function' ) {
							window.genzeeOpenCartDrawer();
						}
						return;
					}

					throw new Error( payload && payload.data && payload.data.message ? payload.data.message : 'Items could not be added.' );
				} )
				.catch( function () {
					if ( form ) {
						form.submit();
					}
				} )
				.finally( function () {
					setLoading( button, false );
				} );
		}

		bundles.forEach( function ( bundle ) {
			var form = bundle.querySelector( '.pdp-bundle__total-form' );
			var button = bundle.querySelector( '[data-pdp-bundle-submit], .pdp-bundle__total' );

			if ( form ) {
				form.addEventListener( 'submit', function ( event ) {
					event.preventDefault();
					submitBundle( bundle, form, button );
				} );
				return;
			}

			if ( button ) {
				button.setAttribute( 'role', 'button' );
				button.setAttribute( 'tabindex', '0' );
				button.addEventListener( 'click', function () {
					submitBundle( bundle, null, button );
				} );
				button.addEventListener( 'keydown', function ( event ) {
					if ( event.key === 'Enter' || event.key === ' ' ) {
						event.preventDefault();
						submitBundle( bundle, null, button );
					}
				} );
			}
		} );
	}

	function initPdpQuantitySteppers() {
		var quantities = document.querySelectorAll( '.pdp-main__cart .quantity' );
		if ( ! quantities.length ) { return; }

		quantities.forEach( function ( quantity ) {
			var input = quantity.querySelector( 'input.qty' );

			if ( ! input || quantity.querySelector( '.pdp-main__qty-step' ) ) { return; }

			var minus = document.createElement( 'button' );
			var plus = document.createElement( 'button' );

			minus.type = 'button';
			plus.type = 'button';
			minus.className = 'pdp-main__qty-step';
			plus.className = 'pdp-main__qty-step';
			minus.setAttribute( 'aria-label', 'Decrease quantity' );
			plus.setAttribute( 'aria-label', 'Increase quantity' );
			minus.textContent = '-';
			plus.textContent = '+';

			quantity.insertBefore( minus, input );
			quantity.appendChild( plus );

			function step( direction ) {
				var current = parseFloat( input.value ) || 0;
				var min = input.getAttribute( 'min' ) === '' ? 1 : parseFloat( input.getAttribute( 'min' ) || '1' );
				var max = input.getAttribute( 'max' ) === '' ? Infinity : parseFloat( input.getAttribute( 'max' ) || 'Infinity' );
				var stepSize = parseFloat( input.getAttribute( 'step' ) || '1' );
				var next = current + ( direction * stepSize );

				if ( Number.isFinite( min ) ) {
					next = Math.max( min, next );
				}

				if ( Number.isFinite( max ) ) {
					next = Math.min( max, next );
				}

				input.value = String( next );
				input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			}

			minus.addEventListener( 'click', function () { step( -1 ); } );
			plus.addEventListener( 'click', function () { step( 1 ); } );
		} );
	}

	function initCartPageQuantitySteppers() {
		var quantities = document.querySelectorAll( '.cart-page__qty-stepper' );
		if ( ! quantities.length ) { return; }

		quantities.forEach( function ( stepper ) {
			var input = stepper.querySelector( 'input.qty' );
			var minus = stepper.querySelector( '.cart-page__qty-step--minus' );
			var plus = stepper.querySelector( '.cart-page__qty-step--plus' );

			if ( ! input || ! minus || ! plus ) { return; }

			function getBound( attr, fallback ) {
				var raw = input.getAttribute( attr );
				if ( raw === null || raw === '' ) {
					return fallback;
				}

				var parsed = parseFloat( raw );
				return Number.isFinite( parsed ) ? parsed : fallback;
			}

			function step( direction ) {
				var current = parseFloat( input.value ) || 0;
				var min = getBound( 'min', 0 );
				var max = getBound( 'max', Infinity );
				var stepSize = getBound( 'step', 1 );
				var next = current + ( direction * stepSize );

				if ( Number.isFinite( min ) ) {
					next = Math.max( min, next );
				}

				if ( Number.isFinite( max ) ) {
					next = Math.min( max, next );
				}

				input.value = String( next );
				input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			}

			minus.addEventListener( 'click', function () { step( -1 ); } );
			plus.addEventListener( 'click', function () { step( 1 ); } );
		} );
	}

	function initArchiveFilterDrawer() {
		var toggle      = document.querySelector( '.archive-filters-mobile__toggle' );
		var drawer      = document.getElementById( 'archive-filter-drawer' );
		var body        = document.body;
		var closeTimer  = 0;
		var closeButton = drawer ? drawer.querySelector( '.archive-filter-drawer__close' ) : null;

		if ( ! toggle || ! drawer || ! body ) {
			return;
		}

		function openDrawer() {
			window.clearTimeout( closeTimer );
			drawer.hidden = false;

			window.requestAnimationFrame( function () {
				drawer.classList.add( 'is-open' );
				body.classList.add( 'archive-filter-drawer-open' );
				toggle.setAttribute( 'aria-expanded', 'true' );

				if ( closeButton ) {
					closeButton.focus();
				}
			} );
		}

		function closeDrawer( restoreFocus ) {
			if ( drawer.hidden ) {
				return;
			}

			drawer.classList.remove( 'is-open' );
			body.classList.remove( 'archive-filter-drawer-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );

			closeTimer = window.setTimeout( function () {
				drawer.hidden = true;

				if ( restoreFocus ) {
					toggle.focus();
				}
			}, 220 );
		}

		toggle.addEventListener( 'click', function () {
			if ( toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				closeDrawer( false );
				return;
			}

			openDrawer();
		} );

		drawer.querySelectorAll( '[data-filter-close]' ).forEach( function ( closeTarget ) {
			closeTarget.addEventListener( 'click', function () {
				closeDrawer( true );
			} );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && ! drawer.hidden ) {
				closeDrawer( true );
			}
		} );

		window.addEventListener( 'resize', function () {
			if ( window.innerWidth > 1023 && ! drawer.hidden ) {
				closeDrawer( false );
			}
		} );
	}

	function initArchiveGridView() {
		var grid = document.querySelector( '[data-archive-grid]' );
		var toggles = document.querySelectorAll( '[data-archive-grid-view]' );

		if ( ! grid || ! toggles.length ) {
			return;
		}

		function setView( view ) {
			var safeView = view === '3' ? '3' : '4';

			grid.classList.toggle( 'archive-grid--grid3', safeView === '3' );
			grid.classList.toggle( 'archive-grid--grid4', safeView === '4' );

			toggles.forEach( function ( toggle ) {
				var active = toggle.getAttribute( 'data-archive-grid-view' ) === safeView;
				toggle.classList.toggle( 'is-active', active );
				toggle.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
			} );

			try {
				window.localStorage.setItem( 'genzee_archive_grid_view', safeView );
			} catch ( error ) {}
		}

		toggles.forEach( function ( toggle ) {
			toggle.addEventListener( 'click', function () {
				setView( toggle.getAttribute( 'data-archive-grid-view' ) || '4' );
			} );
		} );

		try {
			setView( window.localStorage.getItem( 'genzee_archive_grid_view' ) || '4' );
		} catch ( error ) {
			setView( '4' );
		}
	}

	function initCountdowns() {
		var countdowns = document.querySelectorAll( '.hero-countdown[data-countdown], .iz-countdown[data-countdown], .promo-tile__countdown-blocks[data-countdown], .pdp-main__countdown[data-countdown]' );
		if ( ! countdowns.length ) { return; }

		countdowns.forEach( function ( el, elIdx ) {
			var initial = parseInt( el.getAttribute( 'data-countdown' ), 10 ) || 0;
			var hEl = el.querySelector( '.js-countdown-h' );
			var mEl = el.querySelector( '.js-countdown-m' );
			var sEl = el.querySelector( '.js-countdown-s' );

			if ( ! hEl || ! mEl || ! sEl ) { return; }

			/* Persist remaining seconds in sessionStorage so the countdown survives
			   page refreshes within the same browser session. Key combines the page
			   path and element index so multi-countdown pages each get their own slot. */
			var storageKey = 'gz_cd_' + window.location.pathname + '_' + elIdx;
			var remaining = initial;

			try {
				var stored = sessionStorage.getItem( storageKey );
				if ( stored !== null ) {
					var parsed = parseInt( stored, 10 );
					if ( parsed > 0 ) { remaining = parsed; }
				}
			} catch ( e ) { /* sessionStorage blocked (private mode, etc.) */ }

			function pad( n ) { return String( n ).padStart( 2, '0' ); }

			function tick() {
				if ( remaining < 0 ) { remaining = 0; }
				hEl.textContent = pad( Math.floor( remaining / 3600 ) );
				mEl.textContent = pad( Math.floor( ( remaining % 3600 ) / 60 ) );
				sEl.textContent = pad( remaining % 60 );
				if ( remaining > 0 ) {
					remaining--;
					try { sessionStorage.setItem( storageKey, String( remaining ) ); } catch ( e ) {}
				}
			}

			tick();
			setInterval( tick, 1000 );
		} );
	}

	/* Wire the custom metal/variant swatch buttons to the WooCommerce variation
	   select so that swatch clicks propagate into WC's variation-price engine.
	   For simple products the select won't be found and swatches stay cosmetic. */
	function initPdpSwatches() {
		var panels = document.querySelectorAll( '.pdp-main__options' );
		if ( ! panels.length ) { return; }

		panels.forEach( function ( panel ) {
			var buttons = panel.querySelectorAll( '.pdp-main__option' );
			var labelEl = panel.querySelector( '.pdp-main__options-label' );
			if ( ! buttons.length || ! labelEl ) { return; }

			/* Derive attribute slug from label text, e.g. "METAL: Gold" → "metal" */
			var rawLabel = ( labelEl.textContent || '' )
				.split( ':' )[ 0 ]
				.trim()
				.toLowerCase();

			/* Find matching WooCommerce variation select, if any */
			var wcSelect = null;
			document.querySelectorAll( 'form select[name^="attribute_"]' ).forEach( function ( sel ) {
				var selName = ( sel.getAttribute( 'name' ) || '' )
					.replace( 'attribute_pa_', '' )
					.replace( 'attribute_', '' )
					.toLowerCase();
				if ( selName === rawLabel || selName.indexOf( rawLabel ) !== -1 || rawLabel.indexOf( selName ) !== -1 ) {
					wcSelect = sel;
				}
			} );

			/* If a matching WC select exists, hide its table row — swatches replace it */
			if ( wcSelect ) {
				var row = wcSelect.closest( 'tr' );
				if ( row ) { row.hidden = true; }
			}

			buttons.forEach( function ( button ) {
				button.addEventListener( 'click', function () {
					/* Get option text (textContent includes the empty swatch span — trim clears it) */
					var value = button.textContent.trim();

					/* Update active state */
					buttons.forEach( function ( b ) {
						b.classList.remove( 'is-active' );
						b.setAttribute( 'aria-pressed', 'false' );
					} );
					button.classList.add( 'is-active' );
					button.setAttribute( 'aria-pressed', 'true' );

					/* Update inline label span, e.g. "METAL: <span>Gold</span>" */
					var labelSpan = labelEl.querySelector( 'span' );
					if ( labelSpan ) { labelSpan.textContent = value; }

					/* Sync WooCommerce variation select and fire its change event */
					if ( wcSelect ) {
						var match = Array.from( wcSelect.options ).find( function ( opt ) {
							return opt.text.toLowerCase() === value.toLowerCase() ||
								opt.value.toLowerCase() === value.toLowerCase();
						} );
						if ( match ) {
							wcSelect.value = match.value;
							wcSelect.dispatchEvent( new Event( 'change', { bubbles: true } ) );
						}
					}
				} );
			} );
		} );
	}

	function initStickyAtc() {
		var bar = document.getElementById( 'pdp-sticky-atc' );
		if ( ! bar ) { return; }

		/* No IntersectionObserver support — show bar unconditionally. */
		if ( ! ( 'IntersectionObserver' in window ) ) {
			bar.classList.remove( 'is-hidden' );
			return;
		}

		var mainCart      = document.querySelector( '.pdp-main__cart' );
		var footer        = document.getElementById( 'site-footer' );
		var cartHidden    = false;
		var footerVisible = false;

		function update() {
			if ( cartHidden && ! footerVisible ) {
				bar.classList.remove( 'is-hidden' );
			} else {
				bar.classList.add( 'is-hidden' );
			}
		}

		if ( mainCart ) {
			new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					cartHidden = ! entry.isIntersecting;
					update();
				} );
			}, { threshold: 0 } ).observe( mainCart );
		} else {
			cartHidden = true;
			update();
		}

		if ( footer ) {
			new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					footerVisible = entry.isIntersecting;
					update();
				} );
			}, { threshold: 0 } ).observe( footer );
		}
	}

	function initDashWishlistPreview() {
		var container = document.querySelector( '[data-dash-wishlist]' );
		if ( ! container ) { return; }

		var grid    = container.querySelector( '[data-dash-wishlist-grid]' );
		var emptyEl = container.querySelector( '[data-dash-wishlist-empty]' );
		var config  = window.genzeeWishlist || {};

		var ids = [];
		try {
			var stored = window.localStorage.getItem( 'genzee_wishlist_v1' );
			if ( stored ) {
				var parsed = JSON.parse( stored );
				if ( Array.isArray( parsed ) ) { ids = parsed; }
			}
		} catch ( e ) {}

		ids = ids.filter( function ( id ) { return Number.isFinite( id ) && id > 0; } ).slice( 0, 4 );

		if ( ! ids.length ) {
			if ( grid ) { grid.innerHTML = ''; }
			if ( emptyEl ) { emptyEl.hidden = false; }
			return;
		}

		if ( ! config.ajaxUrl || ! window.fetch || ! grid ) {
			if ( emptyEl ) { emptyEl.hidden = false; }
			return;
		}

		if ( emptyEl ) { emptyEl.hidden = true; }

		var formData = new FormData();
		formData.append( 'action', 'genzee_wishlist_products' );
		formData.append( 'nonce', config.nonce || '' );
		formData.append( 'ids', JSON.stringify( ids ) );

		fetch( config.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: formData } )
			.then( function ( res ) { return res.json(); } )
			.then( function ( payload ) {
				var data = payload && payload.success ? payload.data : null;
				if ( data && data.html ) {
					grid.innerHTML = data.html;
					if ( emptyEl ) { emptyEl.hidden = true; }
				} else if ( emptyEl ) {
					grid.innerHTML = '';
					emptyEl.hidden = false;
				}
			} )
			.catch( function () {
				if ( emptyEl ) {
					grid.innerHTML = '';
					emptyEl.hidden = false;
				}
			} );
	}

	function initLiveToast() {
		var toast = document.getElementById( 'live-toast' );
		if ( ! toast ) { return; }

		var config = window.genzeeLiveToast || {};
		var notifications = Array.isArray( config.notifications ) ? config.notifications : [];

		if ( ! config.enabled || ! notifications.length ) {
			toast.remove();
			return;
		}

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
