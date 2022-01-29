<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/styles.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,400;0,500;1,700&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/4fd5a2cda9.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Header starts -->
        <header id="header">
            <div class="header-container">
                <div class="container">
                    <a href="<?php echo BASE_URL?>" class="header-logo">Reseptisivusto</a>
                    <div id="search-icon" class="drop-down-search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div id="cancel-icon" class="drop-down-search-icon">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
            <!-- Drop down search starts -->
            <div class="drop-down-search hide">
                <div class="container">
                    <div class="input-container">
                        <form class="drop-down-search-form" action="index.php">
                            <input type="search" name="search" class="drop-shadow" placeholder="Hae reseptejä ja ateriakokonaisuuksia" />
                            <div class="icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <button class="button-primary drop-shadow" type="submit">
                                <span class="button-text">Hae</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Drop down search ends -->
        </header>
        <!-- Header ends -->