/**
 * Blog index filters — search box + category chips over the server-rendered
 * cards. Nothing is fetched: cards are only hidden and shown.
 */
(function () {
    var grid = document.getElementById('blGrid');
    if (!grid) return;

    var cards  = Array.prototype.slice.call(grid.querySelectorAll('.bl-card'));
    var chips  = Array.prototype.slice.call(document.querySelectorAll('.bl-chip[data-cat]'));
    var search = document.getElementById('blSearch');
    var count  = document.getElementById('blCount');
    var empty  = document.getElementById('blEmpty');
    var cat    = '';

    // A #category in the URL (links from /features) pre-selects that chip.
    var hash = location.hash.replace('#', '');
    if (hash && chips.some(function (c) { return c.getAttribute('data-cat') === hash; })) cat = hash;

    function apply() {
        var q = (search.value || '').trim().toLowerCase();
        var shown = 0;
        cards.forEach(function (card) {
            var ok = (!cat || card.getAttribute('data-cat') === cat)
                && (!q || card.getAttribute('data-text').indexOf(q) !== -1);
            card.hidden = !ok;
            if (ok) shown++;
        });
        chips.forEach(function (c) { c.setAttribute('aria-pressed', c.getAttribute('data-cat') === cat ? 'true' : 'false'); });
        count.textContent = shown + (shown === 1 ? ' article' : ' articles');
        empty.hidden = shown !== 0;
    }

    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            cat = chip.getAttribute('data-cat');
            try { history.replaceState(null, '', cat ? '#' + cat : location.pathname); } catch (e) {}
            apply();
        });
    });
    search.addEventListener('input', apply);
    apply();
})();
