<?php 
/**
 * Custom Search Form
 * 
 */
?>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
    <div>
        <input class="search-box" type="text" value="" name="s" id="s" placeholder="Search" />
    </div>
    <div class = "cart-icon-container">
		<a href="/cart"><img src="<?php echo get_template_directory_uri();?>/images/cart-icon.svg" alt="cart Icon"></a>
	</div>
</form>