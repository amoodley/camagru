<?php
class DB {

	private static function connect() {
		
		$DB_BASE   = 'SocialNetwork';
		$DB_DSN = 'mysql:host=127.0.0.1;dbname='.$DB_BASE;
		$DB_USER = 'root';
		$DB_PASSWORD = '724274';
	
		$opt = [
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false,
		];

		try {
			$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $opt);
			return $pdo;
		} catch(PDOException $e) {
			echo $e->getMessage();
			die();
		}
	}

	public static function query($query, $params = array()) {
	
		$statement = self::connect()->prepare($query);
		$statement->execute($params);

		if (explode(' ', $query)[0] == 'SELECT') {

			$data = $statement->fetchAll();
			return $data;

		}
	}
}
?>