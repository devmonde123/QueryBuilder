<?php
/**
 * Created by PhpStorm.
 * User: abmes
 * Date: 31.08.2019
 * Time: 23:16
 */

namespace App;


/**
 * Class Pagination
 * @author  Mike van Riel <meskine.abderrazzak@gmail.com>
 * @package App
 */
class Pagination
{
    /**
     * curent page index
     * @var int
     * @throws App\Exceptions\Exceptions
     */
    private $index;

    /**
     * next element (index --) when index > 1
     * @var
     */
    private $next;

    /**
     * next element (previous --) when index < last
     * @var
     */
    private $previous;

    /**
     * first element
     * @var int
     */
    private $first;

    /**
     * page length list
     * @var float
     */
    private $last;

    /**
     * element length
     * @var int
     */
    private $total;

    /**
     * Shop per Page
     * @var
     */
    private $perPage;

    /**
     * limit elemet
     * @var int
     */
    private $limit;

    /**
     * min element
     * @var int
     */
    private $limitMin;

    /**
     * max element
     * @var int
     */
    private $limitMax;

    /**
     * @var
     */
    private $url;

    /**
     *
     * Pagination Pagination.
     *
     * @param int $index PageCurrent default 1
     * @param int $total Total Element
     * @param int $limit number per Page
     */
    public function __construct(int $index = 1, int $total, int $limit)
    {
        $this->limit = $limit;
        $this->first = 1;
        $this->total = $total;
        $this->last = ceil($total / $limit);
        $this->index = $index;
        if ($index > $this->last)
            $this->index = 1;
    }

    /**
     * @return mixed
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getLimitMin(): int
    {
        $this->limitMin = ($this->index - 1) * $this->limit > 1 ? (($this->index - 1) * $this->limit) + 1 : 1;
        return $this->limitMin;
    }

    /**
     * @return mixed
     */
    public function getLimitMax(): int
    {
        $this->limitMax = $this->index * $this->limit;
        if ($this->limitMax >= $this->total)
            return $this->total;

        return $this->getIndex() * $this->limit > 0 ? ($this->index * $this->limit) : $this->total;
    }

    /**
     * @param int $limitmax
     */
    public function setLimitMax(int $limitMax): void
    {
        $this->limitMax = $limitMax;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex(int $index): void
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return (int)$this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getNext(): int
    {
        $this->next = $this->index < $this->last ? $this->index + 1 : $this->index;
        return $this->next;
    }

    /**
     * @return int
     */
    public function getPrevious(): int
    {
        $this->previous = $this->index > $this->first ? $this->index - 1 : $this->index;
        return $this->previous;
    }

    /**
     * @return int
     */
    public function getFirst(): int
    {
        return $this->first;
    }

    /**
     * @param int $first
     */
    public function setFirst(int $first): void
    {
        $this->first = $first;
    }

    /**
     * @return int
     */
    public function getLast(): int
    {
        return $this->last;
    }

    /**
     * @param int $last
     */
    public function setLast(int $last): void
    {
        $this->last = $last;
    }

}

/**
 * Class PaginationManager
 * @author  Mike van Riel <meskine.abderrazzak@gmail.com>
 * @package App\Helpers
 */
class PaginationManager extends Pagination
{

    /**
     *
     * PaginationManager PaginationManager.111
     *
     * @param int $index
     * @param int $total
     * @param int $limit
     *
     */
    public function __construct(int $index, int $total, int $limit)
    {
        parent::__construct($index, $total, $limit);
    }

    /**
     * getPaginationJson
     *
     * public function getPaginationJson()
     * {
     * $data = array();
     * if (parent::getLast() > 1) {
     * $data['previous'] = parent::getPrevious();
     * $data['next'] = parent::getNext();
     * $data['count'] = parent::getTotal();
     * $data['limitMin'] = parent::getLimitMin();
     * $data['limitMax'] = parent::getLimitMax();
     * $data['curentPage'] = parent::getIndex();
     * $data['last'] = parent::getLast();
     * }
     * echo json_encode($data);
     * }*/

    /**
     * getPaginationArray
     */
    public function getPaginationDetails()
    {
        $data = array();
        if (parent::getLast() > 1) {
            $data['previous'] = parent::getPrevious();
            $data['next'] = parent::getNext();
            $data['count'] = parent::getTotal();
            $data['limitMin'] = parent::getLimitMin();
            $data['limitMax'] = parent::getLimitMax();
            $data['curentPage'] = parent::getIndex();
            $data['last'] = parent::getLast();
        }
        return $data;
    }

    public function getPaginationHtml()
    {
        if (parent::getLast() > 1) {
            echo "<nav>";
            echo "<ul class='pagination justify-content-center'>";
            $currenPrevioust = parent::getIndex() == parent::getPrevious() ? 'disabled' : '';
            echo "<li class='page-item $currenPrevioust'> <a class='page-link' href='?page=1'>first</a></li>";
            echo "<li class='page-item $currenPrevioust'> <a class='page-link' href='?page=" . parent::getPrevious() . "'>Previous</a></li>";
            for ($i = parent::getFirst(); $i <= parent::getLast(); $i++) {
                $current = $i == parent::getIndex() ? 'disabled' : '';
                echo "<li class='page-item $current'><a class='page-link' href='?page=$i'>$i</a></li>";
            }
            $currenNext = parent::getIndex() == parent::getNext() ? 'disabled' : '';
            echo "<li class='page-item $currenNext'> <a class='page-link' href='?page=" . parent::getNext() . "'>Next</a></li>";
            echo "<li class='page-item $currenNext'> <a class='page-link' href='?page=" . parent::getLast() . "'>Last</a></li>";
            echo "</ul>";
            echo "</nav>";
        }
    }
}

?>
