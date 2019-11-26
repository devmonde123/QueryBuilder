<?php
/**
 * Created by PhpStorm.
 * User: abmes
 * Date: 28.08.2019
 * Time: 00:10
 */

namespace App\Database;

use PDO;

/**
 * Class DatabasePdo
 * @package App\Database
 */
class DatabasePdo// implements DatabasePdoInrface
{

    /**
     * @var PDO
     */
    private $bdd;

    /**
     * DatabasePdo constructor.
     * @param string $localhost
     * @param string $dbname
     * @param string $username
     * @param string $password
     */
    public function __construct()
    {
        $this->bdd = new PDO("mysql:host=localhost;dbname=pdo;charset=utf8", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
        ]);
    }
    public function getPdo(): \PDO
    {
        return $this->bdd;
    }
}
