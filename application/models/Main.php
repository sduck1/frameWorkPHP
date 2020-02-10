<?php

namespace application\models;
use application\core\Model;

class Main extends Model {

    public $error;

    public function contactValidate($post){
        $nameStrlen = strlen($post['name']);
        $textStrlen = strlen($post['text']);
        if ($nameStrlen < 3 or $nameStrlen > 20 ){
            $this ->error = 'Имя должно содержать от 3 до 20 символов';
            return false;
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $this ->error = 'email не коректно';
            return false;
        } elseif ($textStrlen < 10 or $textStrlen > 500 ){
            $this ->error = 'Текст должен содержать от 10 до 500 символов';
            return false;
        }
        return true;
    }

    public function postsCount(){
        return $this-> db -> column('SELECT COUNT(id) FROM posts');
    }

    public function postsList($route){
        $max = 10;
        $params = [
            'max' => $max,
            'start' =>((($route['page'] ?? 1) - 1) * $max)
        ];
        return $this -> db -> row('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :max', $params);
    }
}