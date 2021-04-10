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

        public function quemSeguir(){
            session_start();
            if($_SESSION['id'] == '' && $_SESSION['nome'] == ''){ //protegendo página pessoal da aplicação
                header('Location: /?login=erro');
            }
            $usuarios = array();
            $pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : ''; 
            if($pesquisa != ''){
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome' , $pesquisa);
                $usuario->__set('id' , $_SESSION['id']);
                $usuarios = $usuario->getAll();
            }
            $this->view->usuarios = $usuarios;
            $this->render('quemSeguir');
        }

        public function acao(){
            session_start();
            if($_SESSION['id'] == '' && $_SESSION['nome'] == ''){ //protegendo página pessoal da aplicação
                header('Location: /?login=erro');
            }
            $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
            $id_usuario_seguido = isset($_GET['id']) ? $_GET['id'] : '';
            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);
            if($acao == 'seguir'){
                $usuario->seguir($id_usuario_seguido);
            }else{
                $usuario->deixarDeSeguir($id_usuario_seguido);
            }
            header('Location: /quem_seguir');
        }
    }
?>