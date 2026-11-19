(function () {
  'use strict';

  function ready(callback) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback);
    } else {
      callback();
    }
  }

  ready(function () {
    var desktopToggle = document.querySelector('.desktop-shop-toggle');
    var desktopPanel = document.querySelector('.desktop-shop-panel');
    var mobileToggle = document.querySelector('.mobile-menu-toggle');
    var mobileMenu = document.querySelector('.mobile-menu');
    var mobileClose = document.querySelector('.mobile-menu-close');
    var backdrop = document.querySelector('.mobile-menu-backdrop');
    var searchToggle = document.querySelector('.mobile-search-toggle');
    var mobileSearch = document.querySelector('.mobile-search-row');

    function closeDesktopMenu() {
      if (!desktopToggle || !desktopPanel) return;
      desktopToggle.setAttribute('aria-expanded', 'false');
      desktopPanel.hidden = true;
    }

    function setMobileMenu(open) {
      if (!mobileToggle || !mobileMenu || !backdrop) return;
      mobileToggle.setAttribute('aria-expanded', String(open));
      mobileMenu.classList.toggle('is-open', open);
      mobileMenu.setAttribute('aria-hidden', String(!open));
      backdrop.hidden = !open;
      document.body.classList.toggle('mobile-menu-open', open);
      if (open) mobileClose.focus();
      else mobileToggle.focus();
    }

    if (desktopToggle && desktopPanel) {
      desktopToggle.addEventListener('click', function () {
        var open = desktopToggle.getAttribute('aria-expanded') !== 'true';
        desktopToggle.setAttribute('aria-expanded', String(open));
        desktopPanel.hidden = !open;
      });
      document.addEventListener('click', function (event) {
        if (!desktopPanel.hidden && !desktopPanel.contains(event.target) && !desktopToggle.contains(event.target)) closeDesktopMenu();
      });
    }

    if (mobileToggle && mobileMenu && mobileClose && backdrop) {
      mobileToggle.addEventListener('click', function () { setMobileMenu(true); });
      mobileClose.addEventListener('click', function () { setMobileMenu(false); });
      backdrop.addEventListener('click', function () { setMobileMenu(false); });
      mobileMenu.addEventListener('click', function (event) {
        if (event.target.closest('a')) setMobileMenu(false);
      });
    }

    if (searchToggle && mobileSearch) {
      searchToggle.addEventListener('click', function () {
        var input = mobileSearch.querySelector('input[type="search"]');
        if (input) input.focus();
      });
    }

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      closeDesktopMenu();
      if (mobileMenu && mobileMenu.classList.contains('is-open')) setMobileMenu(false);
    });
  });
}());
