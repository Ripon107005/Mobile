<?php

class OrderTransaction {

    public function getRecordQuery($tran_id)
    {
        $sql = "SELECT * FROM orders WHERE transaction_id = '$tran_id'";
        return $sql;
    }

    public function saveTransactionQuery($post_data)
    {
        $id = $post_data['cus_id'];
        $email = $post_data['cus_email'];
        $phone = $post_data['cus_phone'];
        $transaction_amount = $post_data['total_amount'];
        $address = $post_data['cus_add1'];
        $transaction_id = $post_data['tran_id'];
        $currency = $post_data['currency'];

        $sql = "INSERT INTO orders (clint_id, email, phone, amount, address, status, transaction_id,currency)
                                    VALUES ('$id', '$email', '$phone','$transaction_amount','$address','Pending', '$transaction_id','$currency')";

        return $sql;
    }

    public function updateTransactionQuery($tran_id, $type = 'Success')
    {
        $sql = "UPDATE orders SET status='$type' WHERE transaction_id='$tran_id'";

        return $sql;
    }

    public function insertInToProductList($data)
    {
        $sql = "INSERT INTO `order_list` (order_id,product_id,quantity,price,total) VALUES {$data} ";
        return $sql;
    }
}

