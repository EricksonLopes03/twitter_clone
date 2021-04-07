<?php 
    namespace App\ModelS;
    use MF\Model\Model;

    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        public function salvar(){
            $query = 'insert into usuarios (nome, email, senha) values (:nome, :email, :senha)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();
            return $this;
        }

        public function validarCadastro(){
            $valido = true;
            if($this->__get('nome') == '' || 
                $this->__get('email') == '' || 
                $this->__get('senha') == '') {
                    $valido = false;
                
            }

            return $valido;
        }

        public function getUsuarioPorEmail(){
            $query = 'select nome, email from usuarios where email = :email';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }

        public function autenticar(){
            
            $query = 'select id, nome, email, senha from usuarios where email = :email and senha = :senha';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
            if($usuario['id'] != '' && $usuario['nome'] != ''){
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);

            }
            
            return $usuario;

        }

    }

?>