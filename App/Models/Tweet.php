<?php 
    namespace App\ModelS;
    use MF\Model\Model;

    class Tweet extends Model{
        private $id;
        private $id_usuario;
        private $tweet;
        private $data;

        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        public function salvar(){
            $query = 'insert into tweets (id_usuario, tweet) values (:id_usuario, :tweet)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':tweet', $this->__get('tweet'));
            $stmt->execute();
            return $this;


        }

        public function getAll(){
            $query = "select tweets.id, tweets.id_usuario, tweets.tweet, DATE_FORMAT(tweets.data, '%d/%m/%Y %H:%i') data, usuarios.nome  from tweets
                join usuarios on usuarios.id = tweets.id_usuario
                where tweets.id_usuario = :id_usuario or tweets.id_usuario in(select id_usuario_seguindo from usuarios_seguidores
                where id_usuario = :id_usuario)
                ORDER by tweets.data desc";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }
          
    }
?>