<!-- Terapkan tema sebelum render agar tidak berkedip -->
<script>
    (function(){
        try {
            var t = localStorage.getItem('pelita-theme');
            if (t === 'dark') document.documentElement.setAttribute('data-theme','dark');
        } catch(e) {}
    })();
    function toggleTema() {
        var root = document.documentElement;
        var gelap = root.getAttribute('data-theme') === 'dark';
        if (gelap) { root.removeAttribute('data-theme'); try { localStorage.setItem('pelita-theme','light'); } catch(e){} }
        else { root.setAttribute('data-theme','dark'); try { localStorage.setItem('pelita-theme','dark'); } catch(e){} }
        var ic = document.getElementById('themeIcon');
        if (ic) ic.className = gelap ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
        if (window.__onThemeChange) window.__onThemeChange(!gelap);
    }
    document.addEventListener('DOMContentLoaded', function(){
        var ic = document.getElementById('themeIcon');
        if (ic && document.documentElement.getAttribute('data-theme') === 'dark') ic.className = 'fa-solid fa-sun';
    });
</script>
