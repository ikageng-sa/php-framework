<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Template</title>

        <link rel="stylesheet" type="text/css" href="<?php assets('css/custom.css')?>">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <header class="masthead text-bg-dark  vh-100">
            <div class="container">
                <div class="row">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="masthead-subheading w-auto h-auto m-0 me-3"><?php echo $status; ?></div>
                        <div class="masthead-heading text-uppercase w-auto h-auto m-0 ps-3 border-start border-2"><?php echo $message; ?></div>
                    </div>
                </div>
            </div>
        </header>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</html>