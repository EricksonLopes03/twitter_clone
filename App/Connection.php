<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=twitter_clone;charset=utf8",
				"root",
				"password" 
			);

			return $conn;

		} catch (\PDOException $e) {
			echo "erro: $e->getMessage() ";
		}
	}
}

?>