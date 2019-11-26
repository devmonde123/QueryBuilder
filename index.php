<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Hello, world!</title>
</head>
<body>
<?php
include "vendor/autoload.php";

use App\Database\DatabasePdo;
use App\Database\QueryBuilder;
use App\PaginationManager;
$bdd = (new DatabasePdo())->getPdo();
if (isset($_GET['remove'])) {
    $queryBuilder = new QueryBuilder($bdd);
    $queryBuilder->delete()->from("users")
        ->where("id=:id")
        ->setParam("id", $_GET['remove'])
        ->excute();
}

$limit = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$offest = $page <= 1 ? 0 : ($page - 1) * $limit;

$queryBuilder = new QueryBuilder($bdd);
$items = $queryBuilder
    ->select('id,firstname,lastname,age')
    ->from('users')
    #->where("age>50")
    ->orderBy('id', 'asc')
    ->limit($limit)
    ->offset($offest);
if (isset($_GET['search'])) {
    $items = $items
        ->where(" firstname like '" . $_GET['search'] . "%' or lastname like '" . $_GET['search'] . "%' or age = '" . $_GET['search'] . "'");
}
$items = $items->fetchAll();
?>
<form>
    <div class="form-group">
        <input type="text" class="form-control" id="wort" placeholder="Chercher" name="search" value="<?= $search; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">firstname</th>
        <th scope="col">lastname</th>
        <th scope="col">Age</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item) { ?>
        <tr>
            <th scope="row"><?= $item['id']; ?></th>
            <td><?= $item['firstname']; ?></td>
            <td><?= $item['lastname']; ?></td>
            <td><?= $item['age']; ?></td>
            <td><a type="button" class="btn btn-danger"
                   href="?remove=<?= $item['id']; ?>&page=<?= $page; ?>">Remove</button></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
<?php
$total = $queryBuilder->from('users')->count();
echo "<br/>Total: " . $total . "<br/>";
$paginationManager = new PaginationManager($page, $total, $limit);
$paginationManager->getPaginationHtml();	

