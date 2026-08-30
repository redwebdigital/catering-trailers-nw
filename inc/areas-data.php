<?php
/**
 * Per-area content for the location pages.
 *
 * Each area gets genuinely different copy. Location pages that are the same
 * text with the town name swapped are treated as thin duplicates and do not
 * rank, so every entry here names real local venues, real trading contexts and
 * the actual council you would register with.
 *
 * To add an area: add a row to $CFG['areas'] in config.php, add an entry here,
 * and create areas/catering-trailers-<slug>.php as a two-line copy of an
 * existing one. It then appears in the nav, the footer and the sitemap.
 */

declare(strict_types=1);

return [

'warrington' => [
  'name' => 'Warrington', 'county' => 'Cheshire',
  'council' => 'Warrington Borough Council',
  'lead' => 'Warrington is where our workshop is, so this is the one town where you can
     drop a trailer in the same morning you ring us. Most repairs from the town itself are
     looked at the day they arrive.',
  'trading' => 'Warrington traders tend to work a mix: town centre pitches around Golden
     Square and Bank Quay, the retail parks off the M62, and the events calendar that runs
     from Walking Day through the summer. Stockton Heath and Lymm pull a different, slower
     weekend crowd that suits coffee and dessert units.',
  'logistics' => 'We are minutes from junctions 20 and 21 of the M6 and junction 9 of the
     M62, which is why we end up collecting from across the region. Collection and delivery
     within Warrington is straightforward and usually same week.',
  'note' => 'You register your unit with Warrington Borough Council environmental health,
     and you need that registration before a street trading consent will be considered.',
],

'manchester' => [
  'name' => 'Manchester', 'county' => 'Greater Manchester',
  'council' => 'Manchester City Council',
  'lead' => 'Manchester is the most competitive street food market in the North, and it is
     also the one where a well built trailer pays for itself fastest. The pitches are there
     if your unit looks the part.',
  'trading' => 'The city runs on curated food halls and events as much as on street
     pitches: the Northern Quarter and Ancoats scene, the seasonal markets that take over
     the city centre from November, and the festival circuit through summer. Operators
     working those spaces are judged on how the trailer looks as much as on the food, which
     is why finish and livery matter more here than almost anywhere.',
  'logistics' => 'Around forty minutes from our workshop on the M62, so collection and
     delivery are routine. We regularly run repairs back and forth for Manchester traders
     mid-season.',
  'note' => 'Manchester City Council handles food business registration and street trading
     consents, and the city centre pitch situation is competitive, so line up your pitch
     before you commission a build rather than after.',
],

'liverpool' => [
  'name' => 'Liverpool', 'county' => 'Merseyside',
  'council' => 'Liverpool City Council',
  'lead' => 'Liverpool traders work a long season, from the waterfront events through to
     the winter markets, and the city has a strong independent food scene that rewards a
     unit with a bit of character.',
  'trading' => 'The waterfront and Albert Dock draw the tourist trade, the Baltic Triangle
     is where the independent operators cluster, and the food and drink festival circuit
     through Sefton Park and the parks brings the big weekends. Match day trade around both
     grounds is its own separate business with its own rhythms.',
  'logistics' => 'Straight down the M62 from us, about forty five minutes. We deliver into
     Liverpool regularly and collect trailers for repair across Merseyside.',
  'note' => 'Registration is with Liverpool City Council, and if you are pitching on the
     highway you will need street trading consent from them as well.',
],

'cheshire' => [
  'name' => 'Cheshire', 'county' => 'Cheshire',
  'council' => 'Cheshire East or Cheshire West and Chester',
  'lead' => 'Cheshire is a different trade to the cities. Weddings, country shows, estate
     events and rural festivals, where a smart trailer earns a premium and a scruffy one
     does not get booked twice.',
  'trading' => 'The county show circuit, the Chester and Nantwich event calendar, the
     wedding and private hire market around Knutsford, Tatton and the estates, plus the
     market towns. Operators here often want a unit that looks good in photographs, because
     that is how private hire bookings happen.',
  'logistics' => 'The whole county is inside an hour of the workshop, and we deliver
     throughout. Rural collections are no problem, we just need reasonable access.',
  'note' => 'Cheshire is split between two authorities. Cheshire East covers Macclesfield,
     Crewe and Congleton. Cheshire West and Chester covers Chester, Northwich and Ellesmere
     Port. Register with whichever covers your base.',
],

'bolton' => [
  'name' => 'Bolton', 'county' => 'Greater Manchester',
  'council' => 'Bolton Council',
  'lead' => 'Bolton punches well above its size for food trading, largely because of one
     of the biggest food festivals in the North West landing on the town centre every year.',
  'trading' => 'The Bolton Food and Drink Festival is the anchor date and traders plan
     their year around it. Beyond that there is steady market and town centre trade, the
     retail park pitches, and a strong local events calendar. Units that can handle a very
     high volume weekend and then work a quieter midweek pitch do best here.',
  'logistics' => 'About half an hour up the M61 from us. Collection and delivery into
     Bolton is routine, and we can usually turn repairs round inside the week.',
  'note' => 'Bolton Council handles registration and street trading. Festival pitches are
     applied for separately and they book up a long way ahead, so get your trailer date
     confirmed before you apply.',
],

'wigan' => [
  'name' => 'Wigan', 'county' => 'Greater Manchester',
  'council' => 'Wigan Council',
  'lead' => 'Wigan sits between us and Manchester and has a solid, steady trading scene
     that does not get the attention the big cities do. Good ground for a first trailer.',
  'trading' => 'Town centre and market trade, the parks and the Haigh estate events, plus
     the borough events calendar through summer. Match day trade is significant. Overheads
     and pitch competition are both lower than in Manchester, which makes it a sensible
     place to start out and build a following before taking on the city.',
  'logistics' => 'Twenty minutes up the M6 from the workshop. One of the easiest areas for
     us to collect from and deliver to, and we do it often.',
  'note' => 'Register with Wigan Council environmental health, and check with them about
     street trading before you commit to a pitch, since consent requirements vary across
     the borough.',
],

];
