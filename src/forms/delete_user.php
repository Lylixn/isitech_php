<?php
    session_start();
    
    require_once "../lib/orm/Query.php";
    require_once "../db/models/Categories.php";
    require_once "../db/models/Product.php";
    require_once "../db/models/Users.php";
    
    use db\models\Categories;
    use db\models\Product;
    use db\models\Users;
    use JetBrains\PhpStorm\NoReturn;
    use lib\orm\Query;
    
    #[NoReturn] function redirect_to_page($err): void
    {
        header('Location: /admin.php?error='.$err);
        exit();
    }
    
    $queryAllUsers = new Query(new Users());
    $allUsers = $queryAllUsers->get();
    
    if (count($allUsers) <= 1) {
        redirect_to_page('only one user');
    }
    
    $queryUser = new Query(new Users());
    $user = $queryUser
        ->where('id', $_SESSION['user'])
        ->get()
        ->first();
    
    if ($user->getData()['role'] !== '1') {
        redirect_to_page('not admin');
    }
    
    if (
        !isset($_GET['id'])
    ) {
        redirect_to_page('empty fields');
    }
    
    $id = $_GET['id'];
    
    if (empty($id)) {
        redirect_to_page('empty fields');
    }
    
    if (!is_numeric($id)) {
        redirect_to_page('invalid fields');
    }
    
    $query = new Query(new Users());
    $user = $query
        ->where('id', $id)
        ->get()
        ->first();
    
    if ($user === null) {
        redirect_to_page('user not found');
    }
    
    $user->delete();
    
    header('Location: /admin.php');
    
