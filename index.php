<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home | Kocha Café</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
    <link rel="icon" href="images/logo/logo_icon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <!-- bootstrap link -->
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
</head>

<body>

    <?php
    include 'connect.php';
    include 'top.php';
    include 'sidebar.php'
    ; ?>
    <div class="main-content">
        <div class="content">
            <h1>WELCOME TO KOCHA Café</h1>
            <h2>Let's discover more</h2>
            <a id="btn1" href="menu.php"><button>Order Now</button></a>
        </div>
    </div>
    </div>




    <div class="container">
        <div class="best-card">
            <div class="row" style="margin-top: 100px ;">
                <div class="col-md-4 py-3 py-md-0">
                    <div class="card">
                        <img class="card-image-top" src="./images/dishes2.jpg" alt="">
                        <div class="card-img-overlay">
                            <h1 class="card-titel">Side Dishes</h1>
                            <p class="card-text">Having some little snacks and fries in your meal</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 py-3 py-md-0">
                    <div class="card">
                        <img class="card-image-top" src="./images/main2.jpg" alt="">
                        <div class="card-img-overlay">
                            <h1 class="card-titel">Iconic Dishes</h1>
                            <p class="card-text">Special cuisine always in Kocha Cafe</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 py-3 py-md-0">
                    <div class="card">
                        <img class="card-image-top" src="./images/card3.png" alt="" height="230px">
                        <div class="card-img-overlay">
                            <h1 class="card-titel">Fast Delivery</h1>
                            <p class="card-text">You can wait your food arrived once you ordered</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>









    <div class="container">
        <div class="banner">
            <h1>Best <span class="change-content"></span></h1>
            <h2>Only in Kocha Cafe</h2>
            <a id="btn2" href="menu.php"><button>View Full Menu</button></a>
        </div>
    </div>


    <div class="container">
        <div class="new-card">
            <div class="row" style="margin-bottom: 50px;">
                <div class="col-md-4 py-3 py-md-0">
                    <div class="card">
                        <img class="card-image-top" src="./images/dishes3.jpg" alt="">
                        <div class="card-body">
                            <h2 class="card-titel text-center">Main Dishes</h2>
                            <h3 class="card-titel text-center"></h3>
                            <p class="card-text text-center">We have many varient dishes such as Fried Rice, Porridge and Spaghetti</p>
                            <a id="btn3" href="menu.php#A2" style="display: block"><button>Order Now</button></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 py-3 py-md-0">
                    <div class="card">
                        <img class="card-image-top" src="./images/juices.jpg" alt="">
                        <div class="card-body">
                            <h2 class="card-titel text-center">Beverages</h2>
                            <h3 class="card-titel text-center"></h3>
                            <p class="card-text text-center" style="line-height: 1.3;">Our Cafe have many type drinks to giving more taste to our customer with Latte or Milk Tea for some sweet taste</p>
                            <a id="btn3" href="menu.php#A11" style="display: block"><button>Order Now</button></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 py-3 py-md-0">
                    <div class="card">
                        <img class="card-image-top" src="./images/salad.jpg" alt="">
                        <div class="card-body">
                            <h2 class="card-titel text-center">Side Dishes</h2>
                            <h3 class="card-titel text-center"></h3>
                            <p class="card-text text-center">Here some real snacks for combination to your own dishes</p>
                            <a id="btn3" href="menu.php#A20" style="display: block"><button>Order Now</button></a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>
</body>

</html>