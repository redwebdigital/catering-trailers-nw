<?php
/**
 * The blog registry.
 *
 * To add a post: create blog/<slug>.php (copy an existing one) and add a row
 * here. The index page, the sitemap and the related links all read from this,
 * so there is only ever one list to keep straight.
 *
 * Newest first.
 */

declare(strict_types=1);

return [
    'catering-trailer-cost-uk' => [
        'title'    => 'How much does a catering trailer cost in the UK?',
        'excerpt'  => 'Real figures for new builds, second hand units and refits, and the four things that actually move the price.',
        'date'     => '2026-08-18',
        'updated'  => '2026-08-18',
        'read'     => 7,
        'category' => 'Buying',
        'image'    => 'catering-trailer-front-three-quarter',
        'alt'      => 'A finished white catering trailer outside the workshop',
    ],
    'catering-trailer-certificates' => [
        'title'    => 'What certificates do you need for a catering trailer?',
        'excerpt'  => 'Gas Safe, electrical, food hygiene and council registration, in the order you need them and with the deadlines that catch people out.',
        'date'     => '2026-08-11',
        'updated'  => '2026-08-11',
        'read'     => 6,
        'category' => 'Rules',
        'image'    => 'catering-trailer-serving-hatch-open',
        'alt'      => 'Serving hatch open on a catering trailer showing the stainless interior',
    ],
    'can-my-car-tow-a-catering-trailer' => [
        'title'    => 'Can my car tow a catering trailer?',
        'excerpt'  => 'Towing weights, licence categories and the one number on your car that decides everything. The mistake we see most often.',
        'date'     => '2026-08-04',
        'updated'  => '2026-08-04',
        'read'     => 6,
        'category' => 'Towing',
        'image'    => 'catering-trailer-hitched-rear',
        'alt'      => 'Catering trailer hitched to a tow vehicle',
    ],
];
