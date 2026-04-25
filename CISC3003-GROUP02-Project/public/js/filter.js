// Simple in-page tag filter for pages that have cards with data-tags.
// Used on careers.html where all cards are rendered at page load.
(function () {
  function updateLive(root, count) {
    let live = root.querySelector('[data-live]');
    if (!live) {
      live = document.createElement('p');
      live.className = 'sr-only';
      live.setAttribute('aria-live', 'polite');
      live.setAttribute('data-live', '');
      root.insertBefore(live, root.firstChild);
    }
    live.textContent = `Showing ${count} item${count === 1 ? '' : 's'}.`;
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-card-filter]').forEach((root) => {
      const cards = [...root.querySelectorAll('[data-tags]')];
      const chips = [...root.querySelectorAll('.tag-filter')];

      function apply(value) {
        let visible = 0;
        cards.forEach((card) => {
          const tags = (card.dataset.tags || '').split(/\s+/).filter(Boolean);
          const show = !value || value === 'all' || tags.includes(value);
          card.hidden = !show;
          if (show) visible++;
        });
        chips.forEach((chip) => {
          chip.setAttribute('aria-pressed', (chip.dataset.filterValue || 'all') === value ? 'true' : 'false');
        });
        updateLive(root, visible);
      }

      chips.forEach((chip) => {
        chip.addEventListener('click', () => apply(chip.dataset.filterValue || 'all'));
      });

      apply('all');
    });
  });
})();
