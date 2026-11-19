(function () {
  'use strict';

  var categories = {
    'cat-earrings': 'Earrings',
    'cat-pendant': 'Pendant Necklace',
    'cat-rings': 'Finger Ring',
    'cat-nose': 'Nose Pin',
    'cat-anklet': 'Anklet'
  };

  function categoryFor(card) {
    var media = card.querySelector('.pdp-recent-media');
    if (!media) return 'Genzee Pick';

    for (var className in categories) {
      if (media.classList.contains(className)) return categories[className];
    }

    return 'Jewellery';
  }

  function enhanceRecentCards() {
    document.querySelectorAll('.pdp-recent-card').forEach(function (card) {
      if (card.dataset.cardSystemApplied === 'true') return;
      card.dataset.cardSystemApplied = 'true';

      var title = card.querySelector('h3');
      if (!title) return;

      var type = document.createElement('span');
      type.className = 'pdp-recent-type';
      type.textContent = categoryFor(card);
      card.insertBefore(type, title);

      var add = document.createElement('button');
      add.type = 'button';
      add.className = 'mini-add';
      add.dataset.name = title.textContent.trim();
      add.innerHTML = 'ADD TO BAG <b>+</b>';
      card.appendChild(add);
    });
  }

  document.addEventListener('DOMContentLoaded', enhanceRecentCards);
}());
