<?php
/** Shared close for a blog post: call to action, then the other posts. */
declare(strict_types=1);
?>
  </div>
</section>

<section class="band band--well">
  <div class="wrap wrap--narrow rise" style="text-align:center">
    <h2 style="font-size:clamp(1.4rem,1.15rem + 1.1vw,2rem)">Want this priced up properly?</h2>
    <p class="lede" style="margin-inline:auto">Send us your sizes and your menu and we
       will come back with a real figure and a real date.</p>
    <div class="btn-row" style="justify-content:center;margin-top:1.5rem">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
    </div>
  </div>
</section>

<?php
$others = array_filter($POSTS, fn($k) => $k !== $SLUG, ARRAY_FILTER_USE_KEY);
if ($others):
?>
<section class="band">
  <div class="wrap">
    <div class="rise"><p class="kicker">Keep reading</p>
      <h2 style="font-size:clamp(1.3rem,1.1rem + 1vw,1.75rem)">More from the workshop</h2></div>
    <div class="gal rise stagger" style="margin-top:1.8rem">
      <?php foreach ($others as $s => $p): ?>
        <figure style="display:flex;flex-direction:column">
          <a href="/blog/<?= e($s) ?>" style="display:block">
            <?= picture($p['image'], $p['alt'],
                ['widths'=>[480,800],'sizes'=>'(max-width:700px) 100vw, 45vw']) ?>
          </a>
          <figcaption style="display:block;padding:1.1rem 1.2rem 1.3rem">
            <span class="tag"><?= e($p['category']) ?></span>
            <h3 style="font-size:1.08rem;margin:.5rem 0 .4rem">
              <a href="/blog/<?= e($s) ?>" style="text-decoration:none;color:var(--text-primary)"><?= e($p['title']) ?></a>
            </h3>
            <p style="margin:0;color:var(--text-secondary);font-size:.93rem"><?= e($p['excerpt']) ?></p>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
</article>

<?php require __DIR__ . '/footer.php'; ?>
