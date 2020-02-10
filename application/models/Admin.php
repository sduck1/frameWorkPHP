<?php

namespace application\models;

use application\core\Model;
use Imagick;
class Admin extends Model {

    public $error;

    public function loginValidate($post){

        $config = require 'application/config/admin.php';

        if ($config['login'] != $post['login'] or $config['password'] != $post['password']){
            $this -> error = 'Логин или пароль указан неверно';
            return false;
        }

        return true;
    }

    public function postValidate($post,$type)
    {
        $nameStrlen = strlen($post['name']);
        $descriptionStrlen = strlen($post['description']);
        $textStrlen = strlen($post['text']);
        if ($nameStrlen < 3 or $nameStrlen > 100 ){
            $this ->error = 'Название должно содержать от 3 до 100 символов';
            return false;
        }elseif ($descriptionStrlen < 3 or $descriptionStrlen > 100 ){
            $this ->error = 'Описание должно содержать от 3 до 100 символов';
            return false;
        } elseif ($textStrlen < 3 or $textStrlen > 1000 ){
            $this ->error = 'Текст должно содержать от 3 до 1000 символов';
            return false;
        }
//        if (empty($_FILES['img']['tmp_name']) and $type == 'add'){
//            $this->error = 'Изображение не выбрано';
//            return false;
//        }

        return true;
    }

    public function postAdd($post){
        $params = [
            'id' => '',
            'name' => $post['name'],
            'description' => $post['description'],
            'text' => $post['text'],
        ];

        $this ->db ->query('INSERT INTO posts VALUES (:id, :name, :description, :text)',$params);
        return $this->db->lastInsetId();
    }

    // imagic не работает, только на хостинге
    public function postUploadImage($path, $id) {
//        $img = new Imagick($path);
//        $img->cropThumbnailImage(1080, 600);
//        $img->setImageCompressionQuality(80);
       // $img->writeImage('public/materials/'.$id.'.jpg');
         move_uploaded_file($path,'public/materials/'.$id.'.jpg');
    }

    public function postEdit($post, $id){
        $params = [
            'id' => $id,
            'name' => $post['name'],
            'description' => $post['description'],
            'text' => $post['text']
        ];
        $this->db->query('UPDATE posts SET name = :name, description = :description, text = :text WHERE id = :id', $params);
    }

    public function isPostExists($id){
          $params = [
              'id' => $id
          ];
          return $this -> db -> column('SELECT id FROM posts WHERE id = :id',$params);
    }

    public function postDelete($id){
        $params = [
            'id' => $id
        ];
        $this -> db -> query('DELETE FROM posts WHERE id = :id',$params);
        unlink("public/materials/".$id.".jpg");
    }

    public function postData($id){
        $params = [
            'id' => $id,
        ];
        return $this->db->row('SELECT * FROM posts WHERE id = :id', $params);
    }

}