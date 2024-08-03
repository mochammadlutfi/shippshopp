<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>2023-09-10</lastmod>
        <priority>1.0</priority>
     </url>
     <url>
         <loc>{{ route('profil') }}</loc>
         <lastmod>2023-09-10</lastmod>
         <priority>1.0</priority>
      </url>
    @foreach ($video as $v)
        <url>
            <loc>{{ route('video.show', $v->slug) }}</loc>
            <lastmod>{{ $v->created_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>