<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data_header['title']; ?></title>
    <!-- Add your CSS files here -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/appointment-form.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
    <!-- Add your JavaScript files here -->
    
    
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.datepick.css"> 
    <script type="text/javascript" src="assets/js/jquery.plugin.js"></script> 
    <script type="text/javascript" src="assets/js/jquery.datepick.js"></script>
    <script src="assets/js/form.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/slick/slick.css"/>
    
    <script src="assets/slick/slick.min.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col">
                     <span class="header-address"><?php echo $data_header['address']; ?></span>
                </div>
                <div class="col">
                    <img src="assets/img/logo-2024-head.png" alt="Logo">
                </div>
                <div class="col">
                    <span class="header-phone"><?php echo $data_header['phone']; ?></span>
                </div>
            </div>
    </header>
