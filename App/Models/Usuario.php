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
         // 

        public function getAll(){
            $query = 'select u.id, u.nome, u.email,
            (select count(*) from usuarios_seguidores as us where us.id_usuario = :id_usuario and 
            us.id_usuario_seguindo = u.id) as seguindo
                    from usuarios as u where u.nome like :nome and u.id != :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%'.  $this->__get('nome') . '%');
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }

        public function seguir($id_usuario_seguido){
            $query = 'insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue('id_usuario_seguindo', $id_usuario_seguido);
            $stmt->execute();
            return true;
        }

        public function deixarDeSeguir($id_usuario_seguido){
            $query = 'delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue('id_usuario_seguindo', $id_usuario_seguido);
            $stmt->execute();
            return true;
        }

        public function getInfoUsuario(){
            $query = 'select nome from usuarios where id = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalTweets(){
            $query = 'select count(*) as total from tweets where id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalUsuariosSeguindo(){
            $query = 'select count(*) as total from usuarios_seguidores where id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalSeguidores(){
            $query = 'select count(*) as total from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }


    }

?>