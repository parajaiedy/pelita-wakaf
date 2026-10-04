@php
    $siteTitle = $siteTitle ?? 'Pelita Aset Parepare';
    $siteDesc  = $siteDesc ?? 'Portal Geospasial Aset Wakaf dan Aset Pemerintah Kota Parepare. Lihat sebaran lokasi, status sertipikat, dan data administratif aset kota.';
    $siteUrl   = url()->current();
    $logoUrl   = asset('logo-pelita.png');
    $themeColor = '#047857';
@endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteTitle }}</title>
<meta name="description" content="{{ $siteDesc }}">
<meta name="keywords" content="Pelita Aset Parepare, BPN Kota Parepare, aset wakaf, aset pemerintah, peta aset Parepare, sertipikat tanah, geospasial Parepare">
<meta name="author" content="Kantor Pertanahan Kota Parepare">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">
<meta name="theme-color" content="{{ $themeColor }}">
<meta name="msapplication-TileColor" content="{{ $themeColor }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="Pelita Aset Parepare">
<meta property="og:title" content="{{ $siteTitle }}">
<meta property="og:description" content="{{ $siteDesc }}">
<meta property="og:url" content="{{ $siteUrl }}">
<meta property="og:image" content="{{ $logoUrl }}">
<meta property="og:locale" content="id_ID">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:title" content="{{ $siteTitle }}">
<meta property="twitter:description" content="{{ $siteDesc }}">
<meta property="twitter:image" content="{{ $logoUrl }}">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

<!-- Canonical -->
<link rel="canonical" href="{{ $siteUrl }}">

<!-- Structured Data: WebSite -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Pelita Aset Parepare",
  "url": "{{ url('/') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/') }}/peta?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<!-- Structured Data: Government Organization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "GovernmentOrganization",
  "name": "Kantor Pertanahan Kota Parepare",
  "url": "{{ url('/') }}",
  "logo": "{{ $logoUrl }}",
  "description": "Portal geospasial aset wakaf dan aset pemerintah Kota Parepare",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Parepare",
    "addressRegion": "Sulawesi Selatan",
    "addressCountry": "ID"
  }
}
</script>
