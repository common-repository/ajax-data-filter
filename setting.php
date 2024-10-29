<?php
 if (isset($_GET['settings-updated']))
    echo esc_attr__('Settings Updated');
?>


<div class="yd_admin-main">

	<div id="yd-header">
		<p> <?php echo esc_attr__('Setting For Edit Elements'); ?> </p>
	</div>

    <form method="post" action="options.php">

        <?php settings_fields('yd_filter'); ?>

        <table>

            <tr>	
                <td><h4> <?php echo esc_attr__('Add "div" To Show Content');?> </h4></td>
            </tr>

            <tr>
                <td> <?php echo esc_attr__('Your Current Post Entry Div');?> </td>
                <td ><input type="text" id="yd_text_field" name="yd_div" placeholder="<?php echo esc_attr__('#div or .div');?>"
                           value="<?php echo esc_attr(get_option('yd_div')); ?>">
						
						   </td>
            </tr>


            <tr>
                <td><h4>  <?php echo esc_attr__('Current loading image');?> </h4></td>
            </tr>


            <tr>


            <td><?php echo esc_attr__('Loading Image');?> </td>
            <?php

            /**
             * defult loading image
             * chanege loading image
             */
            if (get_option('yd_loading_img') && get_option('yd_loading_img') != '') {

               echo '<td><img src="' . esc_url(get_option('yd_loading_img')) . '" /></td>';

            } else {

               echo '<td><img width="100px" src="' . esc_url(plugins_url('/img/1.gif',__FILE__)) . '" /></td>';
            }
            ?>

            </tr>

            <tr>
                <td><h4> <?php echo esc_attr__('Change loading image');?> </h4></td>
            </tr>


            <tr>
                <td> <?php echo esc_attr__('Your Loading Image');?> </td>
                <td><input type="text" id="yd_text_field" value="<?php echo esc_url(get_option('yd_loading_img')); ?>"
                           placeholder="<?php echo esc_attr__('Image Address');?>" name="yd_loading_img" ></td>
            </tr>
			
            <tr>
                <td><h4> <?php echo esc_attr__('results show at most');?> </h4></td>
            </tr>


            <tr>
                <td> <?php echo esc_attr__('results show at most');?></td>
                <td><input type="number" step="1" value="<?php echo esc_attr(get_option('yd_posts_per_page')); ?>"
                           name="yd_posts_per_page"/> <?php echo esc_attr__('Post'); ?>
                </td>
            </tr>


            <tr>
                <td><input type="submit" value="Save" id="yd-submit" name="send"></td>
            </tr>

        </table>

    </form>


</div>