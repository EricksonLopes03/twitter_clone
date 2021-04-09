<?php

    namespace App\Controllers;

    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action{
        public function timeline(){
            session_start();
            if($_SESSION['id'] == '' && $_SESSION['nome'] == ''){ //protegendo página pessoal da aplicação
                header('Location: /?login=erro');
            }else{
                $tweet = Container::getModel('Tweet');
                $tweet->__set('id_usuario', $_SESSION['id']);
                $tweets = $tweet->getAll();
                $this->view->tweets = $tweets;
                $this->render('timeline');
            }

            
        }

        public function tweet(){
            session_start();
            if($_SESSION['id'] == '' && $_SESSION['nome'] == ''){ //protegendo página pessoal da aplicação
                header('Location: /?login=erro');
            }

           $tweet = Container::getModel('Tweet');
           $tweet->__set('tweet', $_POST['tweet']);
           $tweet->__set('id_usuario', $_SESSION['id']);
           $tweet->salvar();
           header('Location: /timeline');



        }
    }
?>