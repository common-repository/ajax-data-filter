 <div id="yd_data-form"> 
 
<?php if(get_option('yd_loading_img') and get_option('yd_loading_img') != ''){ ?>

<input type="hidden" value="<?php  echo esc_url(get_option('yd_loading_img')); ?>" class="image-loading" /> 

<?php } else {?>

<input type="hidden" value="<?php echo esc_url(plugins_url('img/1.gif',__file__)); ?>" class="image-loading" />

<?php } ?>

<input type="hidden" value="<?php  echo esc_attr(get_option('yd_div')); ?>" class="results-div" />

 <form method="post" action="<?php the_permalink(); ?>" id="data_form">

 <?php $terms = get_terms( 'yd_filter' );

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){

    foreach ( $terms as $term ):?>
	
		<input type="checkbox" value="<?php echo  esc_attr($term->name); ?>" name="yd_data[]" class="option" /> <?php echo  esc_attr($term->name); ?><br>
        
<?php endforeach; }?>

 <input type="submit" value="Filter">

 </form>
 
</div>
 
 