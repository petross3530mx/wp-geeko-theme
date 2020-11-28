<?php
if (!is_active_sidebar( 'bottom-widgets' )) { return false; }

if ( is_active_sidebar( 'bottom-widgets' ) ) : ?>
<div class="gc-widgets gc-widgets--bottom">
    <div class="gc-widgets__inner">
      <div class="gc-widgets__grid">
        <?php dynamic_sidebar( 'bottom-widgets' ); ?>
      </div>
    </div>
</div>
<?php endif; ?>
