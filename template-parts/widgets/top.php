<?php
if (!is_active_sidebar( 'top-widgets' )) { return false; }

if ( is_active_sidebar( 'top-widgets' ) ) : ?>
<div class="gc-widgets gc-widgets--top">
    <div class="gc-widgets__inner">
      <div class="gc-widgets__grid">
        <?php dynamic_sidebar( 'top-widgets' ); ?>
      </div>
    </div>
</div>
<?php endif; ?>
