<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap v4.5.2 -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">

    <!-- jQuery v3.5.1 -->
    <script src="<?= base_url(); ?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>

    <!-- Bootstrap v4.5.2 -->
    <script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>

    <title>Document</title>

    <style>
        body {
            background: rgb(204, 204, 204);
        }

        page {
            background: white;
            display: flex;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        page[size="A4"] {
            width: 21cm;
            height: 29.7cm;
        }

        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: 21cm;
        }

        page[size="A3"] {
            width: 29.7cm;
            height: 42cm;
        }

        page[size="A3"][layout="landscape"] {
            width: 42cm;
            height: 29.7cm;
        }

        page[size="A5"] {
            width: 14.8cm;
        }

        page[size="A5"][layout="landscape"] {
            width: 21cm;
            height: 14.8cm;
        }

        img {
            margin-top: 0.5em;
        }

        @media print {

            body,
            page {
                margin: 0;
                box-shadow: 0;
            }

            img {
                margin: 0 1em;
                margin-top: 0.45em;
                margin-bottom: 0;
                width: 39mm;
                height: 14mm;
            }
            
            .img-1 {
                margin-left: 0.8em;
            }
            
            .img-2 {
                margin-left: 1.2em;
            }
            
            .img-3 {
                margin-left: 1.6em;
            }
        }
    </style>
</head>

<body>

    <page size="A5">
        <div class="row">
            <?php
            for ($i = 0; $i < count($img); $i++) {
                if (is_int(($i - 2) / 3)) {
                    $classes = 'img-3';
                } else if (is_int($i / 3)) {
                    $classes = 'img-1';
                } else {
                    $classes = 'img-2';
                }

                echo '<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" style="position: relative; margin-bottom: 1em;">';
                echo '<img src="' . $img[$i] . '" class="' . $classes . '" />';
                echo '<p style="font-size: 1em; margin: 0;">' . $code[$i] . ' <span style="font-size: .8em">' . strtoupper($color[$i]) . '</span></p>';
                // echo '<p style="font-size: 1em;">' . $code[$i] . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </page>

    <script>
        $(document).ready(function() {
            window.print();
        })
    </script>

</body>

</html>