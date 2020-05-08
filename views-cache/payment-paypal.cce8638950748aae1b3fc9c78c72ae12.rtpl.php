<?php if(!class_exists('Rain\Tpl')){exit;}?><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="charset" value="utf-8">
    <input type="hidden" name="business" value="scheilacsoares@gmail.com">

    <input type="hidden" name="item_name_1" value="Frete">
    <input type="hidden" name="item_number_1" value="0">
    <input type="hidden" name="amount_1" value="<?php echo htmlspecialchars( $cart["vlfreight"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"> 
    <input type="hidden" name="quantity_1" value="1">
    <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>
    <input type="hidden" name="item_name_<?php echo htmlspecialchars( $counter1+2, ENT_COMPAT, 'UTF-8', FALSE ); ?>" value="<?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
    <input type="hidden" name="item_number_<?php echo htmlspecialchars( $counter1+2, ENT_COMPAT, 'UTF-8', FALSE ); ?>" value="<?php echo htmlspecialchars( $value1["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
    <input type="hidden" name="amount_<?php echo htmlspecialchars( $counter1+2, ENT_COMPAT, 'UTF-8', FALSE ); ?>" value="<?php echo htmlspecialchars( $value1["vlprice"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
    <input type="hidden" name="quantity_<?php echo htmlspecialchars( $counter1+2, ENT_COMPAT, 'UTF-8', FALSE ); ?>" value="<?php echo htmlspecialchars( $value1["nrqtd"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
    <?php } ?>
    
    <input type="hidden" name="currency_code" value="BRL">  

    <input type="hidden" name="email" value="<?php echo htmlspecialchars( $order["desemail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">

    <!-- <input type="image" name="submit"
      src="https://www.paypalobjects.com/pt_BR/i/btn/btn_buynow_LG.gif"
      alt="PayPal - The safer, easier way to pay online"> -->

</form>
<script>
document.forms[0].submit();
</script>