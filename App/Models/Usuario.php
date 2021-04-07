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

    }

?>