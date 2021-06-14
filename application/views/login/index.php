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

    <!-- START STYLES -->

    <!-- Bootstrap v4.5.2 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">

    <!-- Font Awesome v5.14.0 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/fontawesome/css/all.min.css?v5.14.0">

    <!-- END STYLES -->

    <!-- START PLUGINS -->

    <!-- jQuery v3.5.1 -->
    <script src="<?=base_url();?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>

    <!-- Bootstrap v4.5.2 -->
    <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>

    <!-- END PLUGINS -->

    <style>

        /**
         * ----------------------------------------
         * animation slide-top
         * ----------------------------------------
         */
        @-webkit-keyframes slide-top {
            
            0% {
                -webkit-transform: translateY(0);
                        transform: translateY(0);
        
            }
            100% {
                -webkit-transform: translateY(-100px);
                        transform: translateY(-100px);
            }
        }
        /* End of animation slide-top */

        /**
         * ----------------------------------------
         * animation shake-horizontal
         * ----------------------------------------
         */
        @-webkit-keyframes shake-horizontal {

            0%,
            100% {
                -webkit-transform: translateX(0);
                        transform: translateX(0);
                -webkit-transform: translateY(-100px);
                        transform: translateY(-100px);
            }
            10%,
            30%,
            50%,
            70% {
                -webkit-transform: translateX(-10px);
                        transform: translateX(-10px);
            }
            20%,
            40%,
            60% {
                -webkit-transform: translateX(10px);
                        transform: translateX(10px);
            }
            80% {
                -webkit-transform: translateX(8px);
                        transform: translateX(8px);
            }
            90% {
                -webkit-transform: translateX(-8px);
                        transform: translateX(-8px);
            }
        }
        /* End of animation shake-horizontal */

        .slide-top {
            -webkit-animation: slide-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
                    animation: slide-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }

        .shake-horizontal {
            -webkit-animation: shake-horizontal 0.8s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
                    animation: shake-horizontal 0.8s cubic-bezier(0.455, 0.030, 0.515, 0.955) both;
        }

        #login {
            margin-top: 115px;
            padding: 15px;
            border-color: #ddd;
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0.5,0.5);
        }

        .alert {
            margin: 15px;
            width: 100%;
        }

        .is-invalid {
            color: #dc3545;
        }

        @media (max-height: 375px) {

            body {
                overflow-y: hidden;
            }

            #login {
                margin-top: 75px;
            }

            /**
             * ----------------------------------------
             * animation slide-top
             * ----------------------------------------
             */
            @-webkit-keyframes slide-top {
                
                0% {
                    -webkit-transform: translateY(0);
                            transform: translateY(0);
            
                }
                100% {
                    -webkit-transform: translateY(-60px);
                            transform: translateY(-60px);
                }
            }
            /* End of animation slide-top -->

            /**
             * ----------------------------------------
             * animation shake-horizontal
             * ----------------------------------------
             */
            @-webkit-keyframes shake-horizontal {

                0%,
                100% {
                    -webkit-transform: translateX(0);
                            transform: translateX(0);
                    -webkit-transform: translateY(-60px);
                            transform: translateY(-60px);
                }
                10%,
                30%,
                50%,
                70% {
                    -webkit-transform: translateX(-10px);
                            transform: translateX(-10px);
                }
                20%,
                40%,
                60% {
                    -webkit-transform: translateX(10px);
                            transform: translateX(10px);
                }
                80% {
                    -webkit-transform: translateX(8px);
                            transform: translateX(8px);
                }
                90% {
                    -webkit-transform: translateX(-8px);
                            transform: translateX(-8px);
                }
            }
            /* End of animation shake-horizontal */

        }
        /* End of media max-height */

    </style>

    <script>

        $(document).ready( function () {

            if ($('#login').hasClass('shake-horizontal')) {

                $('#username').addClass('is-invalid');

            } else {

                $('#username').removeClass('is-invalid');

            }

        });

    </script>

</head>

<body>

<div class="container">

    <div id="login" class="col-sm-8 offset-sm-2 col-md-6 offset-md-3 <?=$this->session->flashdata('animation');?> <?=$this->session->flashdata('animation-error');?>">

        <h1>Login</h1>

        <form id="form-signin" method="post" action="<?=site_url('login');?>">

            <div class="row"><?=$this->layout_lib->load_view('layouts/alerts');?></div>

            <div class="form-group">
                <label for="username" class="control-label">Username</label>
                <input type="text" name="username" id="username" 
                       class="form-control" 
                       autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" name="password" id="password" 
                       class="form-control">
            </div>

            <input type="hidden" name="btn-login" value="true">

            <button type="submit" class="btn btn-primary">
                Login
            </button>

        </form>

    </div>
    <!-- /div#login.col~ -->

</div>
<!-- /div.container -->

</body>
</html>
