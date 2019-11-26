<?php
/**
 * Created by PhpStorm.
 * User: abmes
 * Date: 20.09.2019
 * Time: 00:33
 */

namespace App\Database;


interface DatabasePdoInrface
{
    public function getPdo(): \PDO;
}