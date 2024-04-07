<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Style Guide | Admin Panel</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Afacad' rel='stylesheet'>
        <link rel="icon" href="../images/logo/logo_icon_2.png">
        <script src="../script.js"></script>
        <script src="../gototop.js"></script>
    </head>
    <body>
        <?php
            include '../connect.php';
            include '../gototopbtn.php';
            include 'navbar.php';
        ?>
        <div class="container-fluid">
            <div class="col-12 m-auto">
                <div class="admin_page">
                     <div class="breadcrumbs">
                        <a>Admin</a> > <a>Category Path</a> > <a class="active">Current Page Title</a>
                    </div>
                    <div class="page_title">Current Page Title</div>
                    <div class="big_container">
                        <div class="container_header">
                           <i class="fas fa-cog"></i><span>Example Header</span>
                        </div>
                        <hr>
                        <div>
                            <div>
                                <label>Label</label>
                                <input type="text" placeholder="Input style #1">
                            </div>
                            <div>
                                <label>Label</label>
                                <select class="select1">
                                    <option>Option Style #1</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-start flex-column">
                                <label>Label</label>
                                <textarea placeholder="Textarea..."></textarea>
                            </div>
                            <div class="d-flex py-1">
                                <div class="button_1">Button Style #1</div>
                                <div class="button_2">Button Style #2</div>
                            </div>
                            <hr>
                            <div>
                                <div class="table-responsive">
                                    <table class="table table-centered table-nowrap mb-0 rounded">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No.</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Categories</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Availability</th>
                                                <th class="d-flex justify-content-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody border="transparent">
                                            <tr>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td class="d-flex justify-content-center">.</td>
                                            </tr>
                                            <tr>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td class="d-flex justify-content-center">.</td>
                                            </tr>
                                            <tr>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td class="d-flex justify-content-center">.</td>
                                            </tr>
                                            <tr>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td>.</td>
                                                <td class="d-flex justify-content-center">.</td>
                                            </tr>
                                        </tbody> 
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="navigation_container">
                            <div id="pagination"><div class="page-button previous-button">Previous</div><div class="page-button page active-page">1</div><div class="page-button page">2</div><div class="page-button page">3</div><div class="page-button page">4</div><div class="page-button page">5</div><div class="page-button next-button">Next</div></div>
                            <div class="no_results_page"><div>Showing 1 to 10 of 44 results</div></div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <input type="text" class="input2" placeholder="Input style #2">
                        <select class="select2">
                            <option>Option Style #2</option>
                        </select>
                    </div>
                    <div class="d-flex">
                        <a class="icon_button1"><i class="fa fa-pen"></i></a>
                        <a class="icon_button2"><i class="fa fa-pen"></i></a>
                    </div>
                </div>
             </div>
         </div>
    </body>
</html>