<?php
session_start();
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucwords(str_replace('_',' ',$page)) ?> | Dish Discovery</title>
    <link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/jquery-ui.min.css">
    <link rel="stylesheet" href="./css/animate.css">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" href="./summernote/summernote-lite.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/jquery-ui.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./summernote/summernote-lite.min.js"></script>
    <link rel="stylesheet" href="./DataTables/datatables.min.css">
    <script src="./DataTables/datatables.min.js"></script>
    <script src="./Font-Awesome-master/js/all.min.js"></script>
    <script src="./js/wow.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        /* Custom Styles */
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1;
            padding-bottom: 10px; 
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            color: #f8f9fa !important;
        }
        .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #f8f9fa;
        }
        .nav-link {
            color: #f8f9fa !important;
        }
        .nav-link:hover {
            color: #ffc107 !important;
        }
        #page-container {
            padding: 20px;
            margin-top: 60px;
        }
        .dropdown-item {
            color: #343a40 !important;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .modal-content {
            border-radius: 8px;
        }
        .modal-header {
            background-color: #343a40;
            color: #f8f9fa;
            border-bottom: none;
        }
        .modal-footer {
            border-top: none;
        }
        .modal-title {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #ffc107;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e0a800;
        }
        .btn-secondary {
            background-color: #343a40;
        }
        footer {
            background-color: #343a40;
            color: #f8f9fa;
            padding: 1px 0;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 1030; 
        }
    </style>
</head>
<body class="bg-light">
    <main class="d-flex flex-column h-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark position-fixed w-100" id="topNavBar" style="z-index:2">
            <div class="container">
                <a class="navbar-brand" href="./">Dish Discovery</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'home')? 'active' : '' ?>" aria-current="page" href="./">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo in_array($page, array('categories','recipe'))? 'active' : '' ?>" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Categories</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="./?page=categories">List Categories</a></li>
                                <?php 
                                $count_categories = $conn->query("SELECT count(category_id) as `count` FROM `category_list`")->fetchArray()['count'];
                                ?>
                                <?php if($count_categories > 0): ?>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <?php
                                $categories = $conn->query("SELECT * FROM `category_list` order by name asc");
                                $i = 0;
                                while($row = $categories->fetchArray()):
                                $i++;
                                ?>
                                <li><a class="dropdown-item" href="./?page=recipe&cid=<?php echo $row['category_id'] ?>"><?php echo $row['name'] ?></a></li>
                                <?php if($i < $count_categories): ?>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <?php endwhile; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'find')? 'active' : '' ?>" aria-current="page" href="./?page=find">Find</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'about')? 'active' : '' ?>" aria-current="page" href="./?page=about">About Us</a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <a class="btn btn-secondary bg-transparent text-light border-0" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Hi, <?php echo $_SESSION['fullname'] ?></a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="./?page=posts">My Posts</a></li>
                                <li><a class="dropdown-item" href="./?page=manage_account">Manage Account</a></li>
                                <li><a class="dropdown-item" href="Actions.php?a=user_logout">Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-secondary bg-transparent text-light border-0 me-2" href="./?page=login_registration">Login</a>
                        <a class="btn btn-secondary bg-transparent text-light border-0" href="./admin">Admin</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="py-4 w-100 flex-grow-1" id="page-container">
            <?php 
                if(isset($_SESSION['flashdata'])):
            ?>
            <div class="dynamic_alert container">
                <div class="alert alert-<?php echo $_SESSION['flashdata']['type'] ?>">
                    <div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a></div>
                    <?php echo $_SESSION['flashdata']['msg'] ?>
                </div>
            </div>
            <?php unset($_SESSION['flashdata']) ?>
            <?php endif; ?>
            <?php
                include $page.'.php';
            ?>
        </div>
    </main>
    <footer class="fixed-bottom">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Dish Discovery. All Rights Reserved.</p>
        </div>
    </footer>
    <div class="modal fade" id="uni_modal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_secondary" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id="submit" onclick="$('#uni_modal_secondary form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-primary btn-sm rounded-0" id="confirm" onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            var wow = new WOW()
            wow.init();
        })
    </script>
</body>
</html>
