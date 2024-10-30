<?php 
	$col = 12/$params['cols'];
	$col = 'col-sm-'.$col;
?>
<div class="item <?php echo $col; ?>">
	<div class="item-inner">
		<div class="post-image">
			<a href="<?php esc_attr(the_permalink()); ?>">
				<?php the_post_thumbnail($params['image_size']); ?>
			</a>
		</div>		
		<div class="post-info">
			<?php the_category(); ?>
			<a href="<?php esc_attr(the_permalink()); ?>">
				<?php the_title('<h3 class="title">', '</h3>'); ?>
			</a>
		</div>
	</div>
</div>