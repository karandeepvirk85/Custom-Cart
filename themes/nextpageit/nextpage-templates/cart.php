<?php 
/**
 * Cart Table
 */  
$arrCart = Products_Controller::getCartFromSession(); 
?>
<table id="cart-table" class="table table-stripped">
    <thead>
        <tr>
            <th>Image</th>
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
                    <td><img src="<?php echo Theme_Controller::getPostImage($key,'thumbnail');?>"></td>
                    <td><?php echo get_the_title($key);?></td>
                    <td><?php echo $strValue;?></td>
                    <td><?php echo $strValue * Products_Controller::getPoints($key)?></td>
                </tr>
            <?php }
        }
        ?>
    </tbody>
</table>