<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Kocha Cafe</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
  <link rel="icon" href="images/logo/logo_icon.png">
  <style>
    body {
      min-height: 100vh; /* Set minimum height to viewport height */
      margin: 0; /* Remove default margin */
      position: relative; /* Set position relative for footer positioning */
      background-image: url('images/shop.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }
    .row {
      color: white;
    }
    .about-container {
      margin-bottom: 20px; /* Add some space between containers */
      background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
      border-radius: 10px; /* Rounded corners */
      backdrop-filter: blur(5px); /* Apply blur effect */
    }
    .about-content {
      padding: 20px; /* Add padding to the content */
    }
    .image-container img {
      max-width: 100%; /* Make sure images don't exceed their container */
    }
  </style>
</head>

<body>
  <?php
  include 'connect.php';
  include 'top.php';
  include 'sidebar.php';
  ?>
  <div class="row">
    <div class="col-md-8 offset-md-2 mt-5">
      <h1 class="text-center mb-4">About Kocha Cafe</h1>
      <h2 class="text-center mb-4">Kocha Cafe was established in 2024 with the aim of providing our valued customers with a delightful culinary experience. We take pride in offering a selection of iconic foods and beverages meticulously crafted by our passionate team.</h2>
      <div class="row">
        <!-- First Container -->
        <div class="col-md-4">
          <div class="about-container">
            <div class="about-content">
              <p>Refresh your emotion by our Kocha Cafe juices with using fresh fruit to having some freshness</p>
            </div>
            <div class="image-container">
              <img src="images/fruit2.jpg" alt="Kocha Cafe Interior">
            </div>
          </div>
        </div>
        <!-- Second Container -->
        <div class="col-md-4">
          <div class="about-container">
            <div class="about-content">
              <p>Add some side dishes to your meal for double happiness in your meals or share the happiness with your friends</p>
            </div>
            <div class="image-container">
              <img src="images/sides3.jpg" alt="Image 2">
            </div>
          </div>
        </div>
        <!-- Third Container -->
        <div class="col-md-4">
          <div class="about-container">
            <div class="about-content">
              <p>"Sip, smile, and enjoy the bubbles." Kocha Cafe also got some beverages that everyone like is boba tea</p>
            </div>
            <div class="image-container">
              <img src="images/boba.jpg" alt="Image 3">
            </div>
          </div>
        </div>
        
        <!-- Fourth Container (Image on Left) -->
        <div class="col-md-12">
          <div class="about-container">
            <div class="row">
              <div class="col-md-6">
                <div class="about-content">
                  <p>Good food is the foundation of genuine happiness. Kocha Cafe brought you many special cuisines to let our customers goes mouth-watering and say bye-bye to hungry and enjoy our special meals in our Cafe</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="image-container">
                  <img src="images/dishes4.jpg" alt="Image 4">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Fifth Container (Image on Right) -->
        <div class="col-md-12">
          <div class="about-container">
            <div class="row">
              <div class="col-md-6">
                <div class="image-container">
                  <img src="images/coffee bean.jpg" alt="Image 5">
                </div>
              </div>
              <div class="col-md-6">
                <div class="about-content">
                  <p>Good friends and great coffee make the perfect blend. A coffee that taste unique that using our fresh coffee bean that fresh harvest and roast then import to Kocha Cafe. </p>
                </div>
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
