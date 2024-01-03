<?php

namespace application\models;
use application\core\Model;

class Order extends Model {

    public $error;

    public function formValidate($post){
       // $menuItemIdStrlen = strlen($post['menuItemId']);
      //  $menuItemNameStrlen = strlen($post['menuItemId']);
        $phoneStrlen = strlen($post['phone']);
        $firstStrlen = strlen($post['firstName']);
        $lastStrlen = strlen($post['lastName']);
        $addressStrlen = strlen($post['address']);
        //$commentStrlen = strlen($post['comment']);

        if ($firstStrlen < 3 or $firstStrlen > 22 ){
            $this ->error = 'Имя должно содержать от 3 до 22 символов';
            return false;
        } elseif ($lastStrlen < 3 or $lastStrlen > 20 ){
            $this ->error = 'Фамилия должна содержать от 3 до 20 символов';
            return false;
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $this ->error = 'email не коректно';
            return false;
        } elseif ($addressStrlen < 10 or $addressStrlen > 500 ){
            $this ->error = 'Адрес не может бысть пустым';
            return false;
        } elseif ($phoneStrlen < 10 or $phoneStrlen >20 ){
            $this ->error = 'Телефон не коректно веден';
            return false;
        }

        return true;
    }

    public function orderAdd($post){
     //   debug($post);
        $params = [
            'id' => 0,
            'nameClient' => $post['firstName'],
            'price' => $post['price'],
            'menuItemName' => $post['menuItemName'],
            'menuItemId' => $post['menuItemId'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'address' => $post['address'],
            'comment' => $post['comment'],
            'payment' => $post['payment'],
            'delivery' => $post['delivery'],
        ];
//:price, :menuItemName, :menuItemId,

        $this ->db ->row("INSERT INTO orders (id, nameClient, address, email, price, phone, comment, menuItemName, menuItemId, payment, delivery) VALUES ('','".$params['nameClient']."', '".$params['address']."', '".$params['email']."', ".$params['price'].",'".$params['phone']."', '".$params['comment']."','".$params['menuItemName']."', '".$params['menuItemId']."','".$params['payment']."','".$params['delivery']."' )");
//        $this ->db ->row('INSERT INTO orders VALUES (:id, :nameClient, :price,:menuItemName, :menuItemId, :email, :phone, :address, :comment)',$params);
        return $this->db->lastInsertId();
    }

    public function lastId(){
        return $this->db->row('SELECT id FROM orders ORDER BY id DESC');
    }

    public function addUser($post){
        $params = [
            'id' => '',
            'nameClient' => $post['firstName'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'address' => $post['address'],
        ];

        $phone = $this->db->row('SELECT * FROM client');
        foreach ($phone as $val){
            if (in_array($params['phone'],$val)){
                return false;
            }
        }
        $this ->db ->row('INSERT INTO client (id, nameClient, email, phone, address) VALUE ("", "'.$params['nameClient'].'","'.$params['email'].'","'.$params['phone'].'","'.$params['address'].'")');
        return $this->db->lastInsertId();
    }

}
