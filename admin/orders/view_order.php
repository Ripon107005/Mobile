<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php if(isset($_GET['view'])):
require_once('../../config.php');
endif;?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<?php 
if(!isset($_GET['id'])){
    $_settings->set_flashdata('error','No order ID Provided.');
    redirect('admin/?page=orders');
}
$order = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client 
        FROM `orders` o 
        inner join clients c on c.id = o.clint_id 
        where o.id = '{$_GET['id']}' ");
if($order->num_rows > 0){
    $data = $order->fetch_assoc();
    $client=$data['client'];
    $order_type=1;
    $delivery_address=$data['address'];
    $payment_method=$data['payment_method'];
    $status = $data['status'];
}else{
    $_settings->set_flashdata('error','Order ID provided is Unknown');
    redirect('admin/?page=orders');
}
?>
<div class="card card-outline card-primary">
    <div class="card-body">
        <div class="conitaner-fluid">
            <p><b>Client Name: <?php echo $client ?></b></p>
            <?php if($order_type == 1): ?>
            <p><b>Delivery Address: <?php echo $delivery_address ?></b></p>
            <?php endif; ?>
            <table class="table-striped table table-bordered" id="list">
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr>
                        <th>QTY</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $olist = $conn->query("SELECT o.*,p.name,b.name as bname FROM order_list o 
                                inner join products p on o.product_id = p.id 
                                inner join brands b on p.brand_id = b.id where o.order_id = '{$_GET['id']}' ");

                    $row = $olist->fetch_assoc();?>
                    <tr>
                        <td><?php echo $row['quantity'] ?></td>
                        <td>
                            <p class="m-0"><?php echo $row['name']?></p>
                            <p class="m-0"><small>Brand: <?php echo $row['bname']?></small></p>
                           
                        </td>
                        <td class="text-right"><?php echo number_format($row['price']) ?></td>
                        <td class="text-right"><?php echo number_format($row['price'] * $row['quantity']) ?></td>
                    </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='3'  class="text-right">Total</th>
                        <th class="text-right"><?php echo number_format($row['price'] * $row['quantity']) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="col-6">
                <p>Payment Method: <?php echo $payment_method ?></p>
                <p>Payment Status: <?php echo @$paid == 0 ? '<span class="badge badge-light text-dark">Unpaid</span>' : '<span class="badge badge-success">Paid</span>' ?></p>
                <p>Order Type: <?php echo $order_type == 1 ? '<span class="badge badge-light text-dark">For Delivery</span>' : '<span class="badge badge-light text-dark">Pick-up</span>' ?></p>
            </div>
            <div class="col-6 row row-cols-2">
                <div class="col-3">Order Status:</div>
                <div class="col-9">
                <?php 
                    switch($status){
                        case 'Pending':
                            echo '<span class="badge badge-light text-dark">Pending</span>';
	                    break;
                        case 'Packed':
                            echo '<span class="badge badge-primary">Packed</span>';
	                    break;
                        case 'OutOfDelivery':
                            echo '<span class="badge badge-warning">Out for Delivery</span>';
	                    break;
                        case 'Success':
                            echo '<span class="badge badge-success">Success</span>';
	                    break;
                        case 'Pickedup':
                            echo '<span class="badge badge-success">Picked Up</span>';
	                    break;
                        default:
                            echo '<span class="badge badge-danger">Cancelled</span>';
	                    break;
                    }
                ?>
                </div>
                <?php if(!isset($_GET['view'])): ?>
                <div class="col-3"></div>
                <div class="col">
                    <button type="button" id="update_status" class="btn btn-sm btn-flat btn-primary">Update Status</button>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>
<?php if(isset($_GET['view'])): ?>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<style>
    #uni_modal>.modal-dialog>.modal-content>.modal-footer{
        display:none;
    }
    #uni_modal .modal-body{
        padding:0;
    }
</style>
<?php endif; ?>
<script>
    $(function(){
        $('#list td,#list th').addClass('py-1 px-2 align-middle')
        $('#update_status').click(function(){
            uni_modal("Update Status", "./orders/update_status.php?oid=<?php echo $id ?>&status=<?php echo $status ?>")
        })
    })
</script>