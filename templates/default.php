<div class="c4d-related-post">
	<div class="row">
	<?php while ( $q->have_posts() ) : ?>
		<?php $p = $q->the_post(); ?>
		<?php require dirname(__FILE__). '/__item.php'; ?>
	<?php endwhile; // end of the loop. ?>
	</div>
</div>