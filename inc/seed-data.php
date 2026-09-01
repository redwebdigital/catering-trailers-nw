<?php
/**
 * The SEO field values every page starts with.
 *
 * Seeded into the pages table once, then owned by the admin area — the seed
 * only ever fills a field that is still empty, so nothing typed under
 * Pages & SEO is ever overwritten by a deploy.
 *
 * Keys map directly onto columns in the pages table.
 */

declare(strict_types=1);

return [

'/' => [
    'label'      => 'Home',
    'file'       => 'index.php',
    'seo_title'  => 'Catering Trailers North West | Bespoke Trailer Builds',
    'meta_desc'  => 'Bespoke catering trailers built for food and hospitality businesses across the North West. New builds, repairs, refurbishments and trailer hire.',
    'focus_kw'   => 'catering trailers North West',
    'h1'         => 'Bespoke Catering Trailers Built for Your Business',
    'hero_intro' => 'Professional catering trailers designed around your menu, equipment and working layout.',
    'schema_type'=> 'WebPage',
],

'/new-catering-trailers' => [
    'label'      => 'New Catering Trailers',
    'file'       => 'new-catering-trailers.php',
    'seo_title'  => 'New Catering Trailers | Bespoke Builds North West',
    'meta_desc'  => 'New bespoke catering trailers designed around your menu, equipment and business. Single and twin axle catering trailer builds across the North West.',
    'focus_kw'   => 'new catering trailers',
    'h1'         => 'New Bespoke Catering Trailers',
    'hero_intro' => 'Build your catering trailer around your business from day one.',
    'schema_type'=> 'Service',
],

'/catering-trailer-repairs' => [
    'label'      => 'Catering Trailer Repairs',
    'file'       => 'catering-trailer-repairs.php',
    'seo_title'  => 'Catering Trailer Repairs | North West',
    'meta_desc'  => 'Professional catering trailer repairs across the North West, including body damage, chassis repairs, doors, serving hatches, interiors and upgrades.',
    'focus_kw'   => 'catering trailer repairs',
    'h1'         => 'Catering Trailer Repairs',
    'hero_intro' => 'Damaged, worn or ageing catering trailer?',
    'schema_type'=> 'Service',
],

'/refurbishments-upgrades' => [
    'label'      => 'Refurbishments & Upgrades',
    'file'       => 'refurbishments-upgrades.php',
    'seo_title'  => 'Catering Trailer Refurbishments & Upgrades',
    'meta_desc'  => 'Upgrade your catering trailer with new interiors, worktops, hatches, electrics, water systems, equipment and professional refurbishment work.',
    'focus_kw'   => 'catering trailer refurbishment',
    'h1'         => 'Catering Trailer Refurbishments & Upgrades',
    'hero_intro' => 'Improve an existing catering trailer without necessarily starting again.',
    'schema_type'=> 'Service',
],

'/gallery' => [
    'label'      => 'Gallery',
    'file'       => 'gallery.php',
    'seo_title'  => 'Catering Trailer Builds & Projects | Gallery',
    'meta_desc'  => 'View catering trailer builds, repairs, refurbishments and custom trailer work from Catering Trailers NW.',
    'focus_kw'   => 'catering trailer builds',
    'h1'         => 'Catering Trailer Builds & Projects',
    'schema_type'=> 'CollectionPage',
],

'/about' => [
    'label'      => 'About',
    'file'       => 'about.php',
    'seo_title'  => 'About Catering Trailers NW | Trailer Builders',
    'meta_desc'  => 'Learn about Catering Trailers NW and our approach to building, repairing and refurbishing professional catering trailers.',
    'focus_kw'   => 'catering trailer builders North West',
    'h1'         => 'About Catering Trailers NW',
    'schema_type'=> 'AboutPage',
],

'/faqs' => [
    'label'      => 'FAQs',
    'file'       => 'faqs.php',
    'seo_title'  => 'Catering Trailer FAQs | Catering Trailers NW',
    'meta_desc'  => 'Answers to common questions about catering trailer builds, costs, sizes, equipment, repairs, refurbishments and quotes.',
    'focus_kw'   => 'catering trailer FAQs',
    'h1'         => 'Catering Trailer FAQs',
    'schema_type'=> 'FAQPage',
],

'/contact' => [
    'label'      => 'Contact',
    'file'       => 'contact.php',
    'seo_title'  => 'Contact Catering Trailers NW | Send an Enquiry',
    'meta_desc'  => 'Contact Catering Trailers NW about new catering trailers, repairs, refurbishments, trailer hire and mobile bar requirements.',
    'focus_kw'   => 'contact catering trailers NW',
    'h1'         => 'Contact Catering Trailers NW',
    'schema_type'=> 'ContactPage',
],

'/request-a-quote' => [
    'label'      => 'Request a Quote',
    'file'       => 'request-a-quote.php',
    'seo_title'  => 'Request a Catering Trailer Quote | Catering Trailers NW',
    'meta_desc'  => 'Request a quote for a new catering trailer, repair, refurbishment, mobile bar or trailer hire from Catering Trailers NW.',
    'focus_kw'   => 'catering trailer quote',
    'h1'         => 'Request a Catering Trailer Quote',
    'schema_type'=> 'WebPage',
],

'/blog' => [
    'label'      => 'Blog',
    'file'       => 'blog/index.php',
    'seo_title'  => 'Catering Trailer Advice & Guides | Blog',
    'meta_desc'  => 'Practical catering trailer advice covering new builds, layouts, equipment, maintenance, repairs, refurbishments and mobile food businesses.',
    'focus_kw'   => 'catering trailer advice',
    'h1'         => 'Catering Trailer Advice & Guides',
    'schema_type'=> 'Blog',
],

'/areas' => [
    'label'      => 'Areas We Cover',
    'file'       => 'areas/index.php',
    'seo_title'  => 'Areas We Cover | Catering Trailers North West',
    'meta_desc'  => 'Catering trailer builds, repairs and refurbishments across Warrington, Manchester, Liverpool, Cheshire and the wider North West.',
    'focus_kw'   => 'catering trailers North West',
    'h1'         => 'Catering Trailers Across the North West',
    'schema_type'=> 'WebPage',
],

'/catering-trailer-hire' => [
    'label'      => 'Trailer Hire',
    'file'       => 'catering-trailer-hire.php',
    'seo_title'  => 'Catering Trailer Hire & Mobile Bar Hire | North West',
    'meta_desc'  => 'Catering trailer, food trailer and mobile bar hire across the North West for events, temporary business use and hospitality requirements.',
    'focus_kw'   => 'catering trailer hire',
    'h1'         => 'Catering Trailer Hire & Mobile Bar Hire',
    'schema_type'=> 'Service',
],

'/privacy' => [
    'label'         => 'Privacy',
    'file'          => 'privacy.php',
    'seo_title'     => 'Privacy Policy | Catering Trailers NW',
    'meta_desc'     => 'Privacy information for visitors and customers of Catering Trailers NW.',
    'h1'            => 'Privacy Policy',
    'robots_index'  => 'noindex',
    'robots_follow' => 'follow',
    'schema_type'   => 'WebPage',
],

];
