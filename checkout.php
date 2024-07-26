<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$total = 0;
$qry = $conn->query("
    SELECT 
        c.*, 
        p.name, 
        i.price, 
        p.id AS pid, 
        cat.category AS catName
    FROM 
        `cart` c 
    INNER JOIN 
        `inventory` i ON i.id = c.inventory_id 
    INNER JOIN 
        `products` p ON p.id = i.product_id 
    INNER JOIN 
        `categories` cat ON cat.id = p.category_id 
    WHERE 
        c.client_id = '" . $_settings->userdata('id') . "'
");

    while($row= $qry->fetch_assoc()):
        $total += $row['price'] * $row['quantity'];
        $productName[]=$row['name'];
        $CategoryName[]=$row['catName'];
    endwhile;
    $productNames = isset($productName) && !empty($productName) ? implode(',',$productName):'';
    $categoryNames = isset($CategoryName) && !empty($CategoryName) ? implode(',',$CategoryName):'';
    $rowCount = $qry->num_rows;

?>
<section class="py-5">
    <div class="container">
        <div class="card rounded-0">
            <div class="card-body"></div>
            <h3 class="text-center"><b>Checkout</b></h3>
            <hr class="border-dark">
            <form action="" id="place_order">
                <input type="hidden" name="amount" value="<?php echo $total ?>">
                <input type="hidden" name="payment_method" value="cod">
                <input type="hidden" name="paid" value="0">
                <div class="row row-col-1 justify-content-center">
                    <div class="col-6">
                    <div class="form-group col mb-0">
                    <label for="" class="control-label">Order Type</label>
                    </div>
                    <div class="form-group d-flex pl-2">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input custom-control-input-primary" type="radio" id="customRadio4" name="order_type" value="1" checked="">
                          <label for="customRadio4" class="custom-control-label">For Delivery</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                          <input class="custom-control-input custom-control-input-primary custom-control-input-outline" type="radio" id="customRadio5" name="order_type" value="2">
                          <label for="customRadio5" class="custom-control-label">For Pick up</label>
                        </div>
                      </div>
                        <div class="form-group col address-holder">
                            <label for="" class="control-label">Delivery Address</label>
                            <textarea id="" cols="30" rows="3" name="delivery_address" class="form-control" style="resize:none"><?php echo $_settings->userdata('default_delivery_address') ?></textarea>
                        </div>
                        <div class="col">
                            <span><h4><b>Total:</b> <?php echo number_format($total) ?></h4></span>
                        </div>
                        <hr>
                        <div class="col my-3">
                        <h4 class="text-muted">Payment Method</h4>
                            <div class="d-flex w-100 justify-content-between">
                                <button class="btn btn-flat btn-dark">Cash on Delivery</button>

                                <button class="your-button-class" id="sslczPayBtn"
                                        token="if you have any token validation"
                                        postdata=''
                                        order="If you already have the transaction generated for current order"
                                        endpoint="checkout_ajax.php"> Pay Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>

function payment_online(){
    $('[name="payment_method"]').val("Online Payment")
    $('[name="paid"]').val(1)
    $('#place_order').submit()
}
$(function(){
    $('[name="order_type"]').change(function(){
        if($(this).val() ==2){
            $('.address-holder').hide('slow')
        }else{
            $('.address-holder').show('slow')
        }
    })
    $('#place_order').submit(function(e){
        e.preventDefault()
        start_loader();
        $.ajax({
            url:'classes/Master.php?f=place_order',
            method:'POST',
            data:$(this).serialize(),
            dataType:"json",
            error:err=>{
                console.log(err)
                alert_toast("an error occured","error")
                end_loader();
            },
            success:function(resp){
                if(!!resp.status && resp.status == 'success'){
                    alert_toast("Order Successfully placed.","success")
                    setTimeout(function(){
                        location.replace('./')
                    },2000)
                }else{
                    console.log(resp)
                    alert_toast("an error occured","error")
                    end_loader();
                }
            }
        })
    })
})
</script>
<script>
    var postData = {
        amount: '<?=$total?>',
        currency: "BDT",
        cus_id: "<?=$_SESSION['userdata']['id']?>",
        cus_name: "<?=$_SESSION['userdata']['firstname']?>",
        cus_email: "<?=$_SESSION['userdata']['email']?>",
        cus_add1: "<?=isset($_SESSION['userdata']['default_delivery_address']) && !empty($_SESSION['userdata']['default_delivery_address']) ? $_SESSION['userdata']['default_delivery_address']:''?>",
        cus_city: "Dhaka",
        cus_postcode: "1216",
        cus_country: "Bangladesh",
        cus_phone: "<?=isset($_SESSION['userdata']['contact']) && !empty($_SESSION['userdata']['contact']) ? $_SESSION['userdata']['contact']:''?>",
        ship_name: "<?=isset($_SESSION['userdata']['firstname']) && !empty($_SESSION['userdata']['firstname']) ? $_SESSION['userdata']['firstname']:''?>",
        ship_add1: "<?=isset($_SESSION['userdata']['default_delivery_address']) && !empty($_SESSION['userdata']['default_delivery_address']) ? $_SESSION['userdata']['default_delivery_address']:''?>",
        ship_city: "Dhaka",
        ship_postcode: "1216",
        ship_country: "Bangladesh",
        product_profile: "general",
        product_name: "<?=$productNames?>",
        num_of_item: "<?=$rowCount?>",
        product_category: "<?=$categoryNames?>"
    };
    document.getElementById('sslczPayBtn').setAttribute('postdata', JSON.stringify(postData));
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "<?php echo base_url ?>assets/js/sslCommerz.js";
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>

<script>

</script>

