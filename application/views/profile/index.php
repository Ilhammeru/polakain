<!doctype html>

<html>

<head>
    <title>
        App
    </title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <meta name="robots" content="NOINDEX,NOFOLLOW">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <!-- Bootstrap v4.5.2 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">

    <!-- Font Awesome v5.14.0 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/fontawesome/css/all.min.css?v5.14.0">

    <!-- jQuery v3.5.1 -->
    <script src="<?=base_url();?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>

    <!-- Bootstrap v4.5.2 -->
    <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>
    

    <style>

        html {
            scroll-behavior: smooth;
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .page {
            width: 100%;
            height: 100vh;
        }

        .carousel-inner, .carousel-indicators {
            position: absolute;
        }

        .logo {
            position: absolute;
            width: 10vh;
            height: 10vh;
            background-color: transparent;
        }

        .nav-item {
            padding: 2vh;
        }

        .nav-link {
            font-size: 20px;
            color: white;
        }

        .par-1 {
            color: white;
            font-size: 5vh;
            position: absolute;
            padding: 22vh 17vh;
        }

        .par-2 {
            color: white;
            font-size: 2vh;
            position: absolute;
            padding: 23vh 5vh;
        }

        .two, .three, .four, .five {
            height: 120vh;
            background-color: #F5DF4D;
        }

        .icon {
            font-size: 5vh;
            position: absolute;
        }

        .p-menu {
            padding-left: 20vh;
        }

        .title {
            font-size: 5vh;
        }

        .p-menu-description {
            font-size: 2vh;
        }

        .img-project-content {
            width: 280px;
            height: 200px;
            background-color: #96999C;
        }

        .img-news-content {
            width: 400px;
            height: 250px;
            background-color: #96999C;
        }

        .project-name {
            font-size: 3vh;
            padding-top: 2vh;
            border-bottom: 0.5vh solid;
            border-color: #96999C;
        }

        .p-about {
            background-color: #000;
            height: 80vh;
        }

        /* General */
        .pr-0 {
            padding-right: 0;
        }

        .pl-0 {
            padding-left: 0;
        }

        .pt-0 {
            padding-top: 0;
        }

        .pb-0 {
            padding-bottom: 0;
        }

        .pt-2 {
            padding-top: 2vh;
        }

        .pl-3 {
            padding-left: 3vh;
        }

        .pr-5 {
            padding-right: 5vh;
        }

        .pb-4 {
            padding-bottom: 4vh;
        }

        .pl-5 {
            padding-left: 5vh;
        }

        .p-6 {
            padding: 6vh;
        }

        .pl-6 {
            padding-left: 6vh;
        }

        .d-block {
            width: 100%;
            height: 100vh;
        }

        .d-inline {
            display: inline;
        }

        .bb {
            border-bottom: 1px;
            border-color: #96999C;
        }

        .footer {
            background-color: #ede8ee;
            width: 100%;
            height: 30vh;
            padding-left: 24vh;
            padding-top: 5vh;
        }

        @media (max-width: 1050px) {
            .img-project-content {
                width: 210px;
                height: 150px;
            }
            .img-project-content {
                width: 240px;
                height: 150px;
            }
        }

    </style>

</head>

<body>

    <div class="main">

        <section class="page one carousel slide" data-ride="carousel" id="carouselExampleIndicators">
            
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block" src="https://placehold.it/900x500/39CCCC">
                </div>
                <div class="carousel-item">
                    <img class="d-block" src="https://placehold.it/900x500/3c8dbc">
                </div>
                <div class="carousel-item">
                    <img class="d-block" src="https://placehold.it/900x500/f39c12">
                </div>
            </div>

            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            </ol>

            <div class="col-12 p-6">

                <div class="row">

                    <div class="col-md-5 col-sm-5">

                        <img class="logo" src="<?=base_url();?>assets/background/logo-ans.png">

                    </div>
                    <!--/.col -->

                    <div class="col-md-7 col-sm-7">
                        
                        <nav class="navbar navbar-expand-md float-right">

                            <div class="navbar-collapse" id="navbarsExampleDefault">

                                <ul class="navbar-nav">

                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Project</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#">About Us</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#">News</span></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Contact</span></a>
                                    </li>

                                </ul>

                            </div>
                            <!--/.navbar-collapse -->

                        </nav>
                        <!--/.navbar -->

                    </div>
                    <!--/.col -->

                </div>
                <!--/.row -->

                <div class="row">

                    <div class="col-md-6 col-sm-6">
                        
                        <div class="par-1">
                            <p>For a better future life.</p>
                        </div>

                    </div>
                    <!--/.col -->

                    <div class="col-md-6 col-sm-6">

                        <div class="par-2">
                            <p>cbdjskbcjkdsbckfdbcjv;dfbjvcbfdkvbfsdk</p>
                        </div>

                    </div>
                    <!--/.col -->

                </div>

            </div>
            <!--/.col -->

        </section>

        <section class="page two">

            <div class="col-12 p-6">

                <div class="row">
                    
                    <i class="fa fa-arrow-right icon pl-6 pt-2"></i>

                    <div class="col-md-12 col-sm-12 p-menu">

                        <p class="title">Project.</p>
                        <p class="p-menu-description">vbdskbvfbsdklvb</p>

                    </div>
                    <!--/.col -->

                </div>
                <!--/.row -->

                <div class="row" style="margin-left: 18vh">

                    <div class="col-xs-4 pt-2 pr-5">
                        <img src="" class="img-project-content">
                        <p class="project-name">
                            AP#001<br>
                            Artisan Studio
                        </p>
                    </div>

                    <div class="col-xs-4 pt-2 pr-5">
                        <img src="" class="img-project-content">
                        <p class="project-name">
                            AP#002<br>
                            Nitip Matoa
                        </p>
                    </div>

                    <div class="col-xs-4 pt-2 pr-5">
                        <img src="" class="img-project-content">
                        <p class="project-name">
                            AP#003<br>
                            Sosial Lab
                        </p>
                    </div>

                    <div class="col-xs-4 pt-2 pr-5">
                        <img src="" class="img-project-content">
                        <p class="project-name">
                            AP#001<br>
                            Artisan Studio
                        </p>
                    </div>

                    <div class="col-xs-4 pt-2 pr-5">
                        <img src="" class="img-project-content">
                        <p class="project-name">
                            AP#002<br>
                            Nitip Matoa
                        </p>
                    </div>

                    <div class="col-xs-4 pt-2 pr-5">
                        <img src="" class="img-project-content">
                        <p class="project-name">
                            AP#003<br>
                            Sosial Lab
                        </p>
                    </div>

                </div>
                <!--/.row -->

            </div>
            <!--/.col -->

        </section>

        <section class="page three">

            <div class="col-12 p-6">

                <div class="row">
                    
                    <i class="fa fa-arrow-right icon pl-6 pt-2"></i>

                    <div class="col-md-12 col-sm-12 p-menu">

                        <p class="title">About</p>
                        <p class="p-menu-description">vbdskbvfbsdklvb</p>

                    </div>
                    <!--/.col -->

                </div>
                <!--/.row -->

                <div class="row pl-5 pr-5">

                    <div class="col-sm-6 p-about">
                        
                        <div class="par-1">
                            <p>PT. Ansena Grup Asia.</p>
                        </div>

                    </div> 

                    <div class="col-sm-6 p-about">
                        
                        <div class="par-2">
                            <p>sfkbdsk dvsnfknv</p>
                        </div>

                    </div> 

                </div>
                <!--/.row -->

            </div>
            <!--/.col -->

        </section>

        <section class="page four">

            <div class="col-12 p-6">

                <div class="row">
                    
                    <i class="fa fa-arrow-right icon pl-6 pt-2"></i>

                    <div class="col-md-12 col-sm-12 p-menu">

                        <p class="title">News</p>
                        <p class="p-menu-description">vbdskbvfbsdklvb</p>

                    </div>
                    <!--/.col -->

                </div>
                <!--/.row -->

                <div class="row pb-4" style="margin-left: 18vh">

                    <div class="col-xs-6 pt-2 pr-5">
                        <img src="" class="img-news-content">
                    </div>

                    <div class="col-xs-6 pt-2 pr-5">
                        <h2>#Decade Ansena</h2>
                        <p>vdsbvjkdfsbvkj</p>
                    </div>

                </div>
                <!--/.row -->

                <div class="row pb-4" style="margin-left: 18vh">

                    <div class="col-xs-6 pt-2 pr-5">
                        <img src="" class="img-news-content">
                    </div>

                    <div class="col-xs-6 pt-2 pr-5">
                        <h2>#Decade Ansena</h2>
                        <p>vdsbvjkdfsbvkj</p>
                    </div>

                </div>
                <!--/.row -->

                <div class="row pb-4" style="margin-left: 18vh">

                    <div class="col-xs-6 pt-2 pr-5">
                        <img src="" class="img-news-content">
                    </div>

                    <div class="col-xs-6 pt-2 pr-5">
                        <h2>#Decade Ansena</h2>
                        <p>vdsbvjkdfsbvkj</p>
                    </div>

                </div>
                <!--/.row -->

            </div>
            <!--/.col -->

        </section>

        <section class="page five">

            <div class="col-12 p-6">

                <div class="row">
                    
                    <i class="fa fa-arrow-right icon pl-6 pt-2"></i>

                    <div class="col-md-12 col-sm-12 p-menu">

                        <p class="title">Contact</p>

                    </div>
                    <!--/.col -->

                </div>
                <!--/.row -->

                <div class="row pb-4" style="margin-left: 18vh">

                    <div class="col-xs-12 pt-2 pr-5">
                        <h4>ADDRESS</h4>
                        <p>fknsdlsnvldfskn</p>
                        <h4>EMAIL</h4>
                        <p>vfdksv sdfk</p>
                    </div>

                </div>
                <!--/.row -->

            </div>  

        </section>

    </div>
    <!--/.main -->

    <div class="footer">

        <div class="row">

            <div class="col-md-12">
                <h1 class="title-footer">Ansena Grup Asia</h1>
                <h5 class="subtitle-footer">for a better future life</h5>
            </div>

        </div>

        <div class="row">

            <div class="col-md-12">
                <p>vbdsbvkfd bkvf</p>
            </div>

        </div>

        <div class="row">

            <div class="col-md-12">
                <p>FB IG</p>
            </div>

        </div>

    </div>

</body>
</html>