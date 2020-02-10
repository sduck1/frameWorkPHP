<?php

namespace application\controllers ;

use application\core\Controller;
use application\lib\Pagination;
use application\models\Admin;

class MainController extends Controller {
    //page index
    public function indexAction(){
        $pagination = new Pagination($this->route,$this->model->postsCount());
        $vars = [
            'pagination' => $pagination->get(),
            'list' => $this->model->postsList($this->route)
        ];
        $this ->view-> render('MAin page',$vars);
    }
    //page about
    public function aboutAction(){

        $this ->view-> render('About page');
    }
    //page contact
    public function contactAction(){
        if (!empty($_POST)){
            if (!$this -> model -> contactValidate($_POST)){
                $this ->view->message('error',$this->model->error);
            }
            mail('sduck1@yandex.ru','Сообщение из блога от '.$_POST['name'],$_POST['text']);
            $this ->view->message('success','Сообщение отправлено');
        }
        $this ->view-> render('Contact page');
    }
    //page post
    public function postAction(){
        $adminModel = new Admin;
        if (!$adminModel->isPostExists($this->route['id'])){
            $this -> view -> errorCode(404);
        }
        $vars = [
            'data' => $adminModel->postData($this->route['id'])[0]
        ];
        $this ->view-> render('Post page',$vars);
    }

}
