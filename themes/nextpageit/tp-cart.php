<?php 
/**
 * Template Name: Cart
 */
get_header();
    $arrCart = Products_Controller::getCartFromSession();
?>
<div class="page-container">
    <table id="cart-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if(!empty($arrCart)){
                foreach($arrCart as $key => $strValue){
                    ?>
                    <tr>
                        <td><?php echo get_the_title($key)?></td>
                        <td><?php echo $strValue;?></td>
                        <td><?php echo $strValue * Products_Controller::getPoints($key)?></td>
                    </tr>
                <?php }
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#cart-table').DataTable();
    });
</script>
<?php get_footer();?>