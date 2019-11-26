<?php
/**
 * Created by PhpStorm.
 * User: abmes
 * Date: 08.09.2019
 * Time: 18:07
 */

namespace App\Database;

use PDO;
//use App\Modules\Category\CategoryEntity;
use App\Database\DatabasePdo;


/**
 * Class QueryBuilder
 * @package App\Database
 */
class QueryBuilder
{

    /**
     * @var PDO
     */
    private $bdd;
    /**
     * @var array
     */
    private $fields = ["*"];
    /**
     * @var
     */
    private $from;
	/**
     * @var
     */
    private $delete;
    /**
     * @var array
     */
    private $order = [];
    /**
     * @var
     */
    private $limit;
    /**
     * @var
     */
    private $offset;
    /**
     * @var int
     */
    private $page = 0;
    /**
     * @var
     */
    private $where;
    /**
     * @var array
     */
    private $params = [];

    /**
     * QueryBuilder constructor.
     */
    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    /**

     */
    public function getPdo(DatabasePdo $bdd): DatabasePdo {
        //$this->bdd = $bdd;
    }

    public function fetch(string $field)
    {
        $query = $this->bdd->prepare($this->toSQL());
        $query->execute($this->params);
        $result = $query->fetchAll();
        if ($result === false) {
            return null;
        }
        return $result ?? null;
    }

    /**
     * @param mixed ...$fields
     * @return QueryBuilder
     */
    public function select(...$fields): self
    {
        if(is_array($fields[0])){
            $fields = $fields[0];
        }
        if($this->fields  == ['*']){
            $this->fields = $fields;
            return $this;
        }
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    /**
     * @param string $table
     * @param string|NULL $alias
     * @return QueryBuilder
     */
    public function from(string $table, string $alias = NULL): self
    {
        $this->from = $alias === null ? "$table" : "$table $alias";
        return $this;
    }

    /**
     * @param string $table
     * @param string|NULL $alias
     * @return QueryBuilder
     */
    public function delete(): self
    {
		$this->delete = true;
        return $this;
    }

    /**
     * @param int $limit
     * @return QueryBuilder
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $offset
     * @return QueryBuilder
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int $page
     * @return QueryBuilder
     */
    public function page(int $page): self
    {
        return $this->offset($this->limit * ($page - 1));
    }

    /**
     * @param string $where
     * @return QueryBuilder
     */
    public function where(string $where): self
    {
        $this->where = "$where";
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return QueryBuilder
     */
    public function setParam(string $key, string $value): self
    {
         $this->params[$key] = $value;
         return $this;
    }

    /**
     * @param string $key
     * @param string $direction
     * @return QueryBuilder
     */
    public function orderBy(string $key, string $direction): self
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            $this->order[] = $key;
        } else {
            $this->order[] = "$key $direction";
        }
        return $this;
    }

    /**
     * @param $object
     * @return null
     */
    public function fetchByOne($object)
    {

        $query = $this->bdd->prepare($this->toSQL());
        $query->execute($this->params);
        $result = $query->fetchObject($object);
        if ($result === false) {
            return null;
        }
        return $result ?? null;
    }


    /**
     * @return array|null
     */
    public function fetchAll(): ?array
    {
        $response = array();
        $query = $this->bdd->prepare($this->toSQL());
        $query->execute($this->params);
        $items = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($items as $key => $data) {
            array_push($response, get_object_vars($data));
        }
        return $response;
    }

    /**
     * @param DatabasePdo $pdo
     * @param string $sql
     */
    public function excute()
    {
        //die("ddd");
        $query = $this->bdd->prepare($this->toSQL());
        $query->execute($this->params);
    }

    /**
     * @return int
     */
    public function count(): int{
		$this->fields = [];
		$this->offset = null;
		$this->limit = null;
		$this->order = null;
        return (int)(clone $this)->select('count(id) as count')->fetch('count')[0]->count;
    }

    /**
     * @return string
     */
    public function toSQL(): string
    {
        $fields = implode(', ', $this->fields);
        $query = "SELECT $fields FROM {$this->from}";
        if (!empty($this->select)) {
            $query = "SELECT {implode(', ',$this->select)} FROM {$this->from}";
            echo $query;
        }
		if(!empty($this->delete)){
			$fields = '';
			$query = "delete FROM {$this->from}";
		}
        if ($this->where) {
            $query .= " WHERE " . $this->where;
        }
        if (!empty($this->order)) {
            $query .= " ORDER BY " . implode(', ', $this->order);
        }
        if ($this->limit > 0) {
            $query .= " LIMIT " . $this->limit;
        }
        if ($this->offset !== null) {
            $query .= " OFFSET " . $this->offset;
        }
        if ($this->page !== 0) {
            $query .= " OFFSET " . $this->offset;
        }
        return $query;
    }
}