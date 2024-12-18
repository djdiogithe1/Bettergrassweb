<?php
// Start session and verify login
session_start();
if (!isset($_SESSION['CUST_ID'])) {
    header("Location: ../User_login/login.php");
    exit();
}

// Include database connection
include_once '../User_profile/db_connection.php';

// Get the user ID from session
$user_id = $_SESSION['CUST_ID'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        /* Dark Mode Base Styles */
        body {
            background-image: url("../assets/img/grass.jpg");
            color: #e0e0e0; /* Light text for contrast */
            font-family: 'Lato', sans-serif;
        }
        .card {
            background-color: #1c1c1c; /* Dark card background */
            color: #fff;
            border: none;
            margin: 30px auto; /* Center the card */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px #00bcd4; /* Neon aqua glow */
            transition: box-shadow 0.3s ease;
            max-width: 600px;
        }
        .card:hover {
            box-shadow: 0 0 30px #00bcd4; /* Enhanced glow on hover */
        }
        .form-group label {
            color: #00bcd4; /* Aqua text color for labels */
            font-weight: bold;
        }
        .form-control {
            background-color: #111;  /* Dark background for form fields */
            color: #00bcd4; /* Aqua text color for inputs */
            border: 1px solid #555;
            padding: 10px;
            font-size: 16px;
            width: 100%;  /* Full-width fields */
            border-radius: 5px;
        }
        .form-control:focus {
            background-color: #444;
            border-color: #00bcd4;
            color: #00bcd4;
        }
        .btn-primary {
            background-color: #00bcd4;
            border: none;
            font-size: 16px;
            padding: 12px 20px;
            width: 100%;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #008c99;
        }
        footer {
            background-color: #222;
            color: #aaa;
            text-align: center;
            padding: 15px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .table-bordered {
            border: 1px solid #444;
        }
        .table th, .table td {
            color: #00bcd4;
        }
        /* Glow effect for outer boxed area */
        .profile {
            box-shadow: 0 0 25px #00bcd4; /* Neon aqua glow */
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }
        
        /* Navigation Bar Styling */
        nav {
            background-color: #1c1c1c;
            padding: 15px 0;
        }
        nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .logo {
            font-size: 24px;
            color: #00bcd4;
            text-decoration: none;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav ul li a {
            color: #e0e0e0;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        nav ul li a:hover {
            color: #00bcd4;
        }
        nav ul li a.active {
            color: #00bcd4;
            font-weight: bold;
        }

        /* Provider List Styling */
        .provider-list {
            list-style: none;
            padding: 0;
        }
        .provider-list li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .provider-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #00bcd4;
        }
        .provider-info {
            color: #00bcd4;
        }
        .provider-info span {
            font-weight: bold;
        }

        /* Styling for checkboxes and labels */
        .provider-check {
            margin-bottom: 10px;
        }
        .provider-check input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav>
            <div class="container">
                <a href="../index.html" class="logo">BetterGrassNow</a>
                <ul>
                    <li><a href="../User_profile/home.php">HOME</a></li>
                    <li><a href="../User_profile/inbox.php">INBOX</a></li>
                    <li><a href="../User_profile/services.php">REQUEST A QUOTE</a></li>
                    <li><a href="../User_profile/service_requests.php">SERVICE LOG</a></li>
                    <li><a href="logout.php">SIGN OUT</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Service Order Form -->
    <div class="container">
        <div class="card">
            <h2 class="text-center">Terms & Conditions</h2>
            <small>
			<strong>1)&nbsp;Business statement-</strong>
			<br>&nbsp;&nbsp;This website is intended as a 
			service provider platform to provide local lawn services with a way to communicate, quote 
			services requested, and collect payment.&nbsp; Any other purposes with result in account 
			terminiation.&nbsp; All service providers must pass a background check prior to receive 
			service requests.&nbsp; These terms service as legal binding agreement between Bettergrassnow, 
			customers and providers being a binding legal agreement.<br>
			<br>
			<strong>2)&nbsp;Lawn mowing service-</strong>
			<br>&nbsp;&nbsp;Lawn mowing service only includes grass mowing, weed whacking, edging, and leaf 
			blowing.&nbsp; This service does not include any other additional requests such as leaf 
			bagging, bagging grass clippings, tree trimming, hedge trimming or yard clean-up as 
			these are additional paid services.&nbsp;<br>
			<br>
			<strong>3)&nbsp;Hedge trimming services-</strong>
			<br>&nbsp;&nbsp;Hedge trimming is able to be quoted upon request.&nbsp;This service is additional 
			and not apart of standard mowing.&nbsp;The price for hedge trimming varies depending on how many 
			hedges, how tall and the overall footage of the hedges requested to be trimmed.&nbsp;The provider 
			is require to trim the hedges, bag the clippings and haul the debris for disposal unless scheduled 
			for a bulk trash day.&nbsp; The use of bulk trash requires the trash area to be according to local 
			regulations for bulk trash pick up according to pile size and must be scheduled the day prior to 
			bulk trash.&nbsp;<br>
			<br>
			<strong>4)&nbsp;Payment and cancellation policy-</strong>
			<br>&nbsp;&nbsp;After the customer places a service quote request the quote is approved, payment 
			will be requested 3 business days prior to the requested or scheduled service day requested.
			&nbsp; Customers may request service as early as 24 hours in advance.&nbsp;Once a notification 
			that the provider is in route the service request may not be cancelled unless there is a 
			complication which needs escalation.<br>
			<br>
			<strong>5)&nbsp;Customer responsibility and obligation-</strong>
			<br>&nbsp;&nbsp;It is the responsibility of the customer to provide open access to the provider 
			at the time when services are rendered.&nbsp;There are to be no pets in the yard at the time of 
			service, if the pets are unable to be secured at the time of service, the service provider
			may complete the full payment without full completion of the yard. Confirmation of the animal 
			in the yard may be needed to support the claim.&nbsp; If the provider is unable to access any part 
			of the yard, the service provider will be paid the full amount of the service request.&nbsp; 
			If the yard is not in need of service, the provider reserves the right to skip the mowing, 
			without pay until the following service date.&nbsp;The customer also has a responsibility to 
			provide the current condition of thier yard.&nbsp;That means that if the grass is tall due to 
			lack of care please report the condition to provide the quote accurately.&nbsp;Customers and 
			providers alike to treat everyone kind and with respect.&nbsp;Failure to do so will result in 
			service cancellation and account termination.&nbsp;<br>
			<br>
			<strong>6)&nbsp;Provider responsibility and obligation-</strong>
			<br>&nbsp;&nbsp;As a provider on the Bettergrassnow platform, you have an obligation to provide 
			quality service on the agreed scheduled date unless an emergency or situation occurs and you 
			are unable to make the scheduled service request. Providers are expected to be professional 
			as well as careful for property damage. If damage occurs the provider must correct the 
			situation as Bettergrassnow only provides the platform for professional lawn service 
			providers.&nbsp;Lawn service providers must also conduct themselves in a professional
			manner. If the situation arrises where the custmoer asks the provider to leave, kindly 
			do so. If services were rendered complete the job status and provide the documents requested 
			for completion.&nbsp;Customers and providers alike to treat everyone kind and with 
			respect.&nbsp;Failure to do so will result in service cancellation and account termination.&nbsp;<br>
			</small>
        </div>
    </div>

	<div class="footer">
            <div class="footer-content">
                <footer>
                    2023-2024 BetterGrassNow&copy;
                            <br><a href="../contact/contact.html">Contact us</a>
        					<br><a href="../sitemap.php">Sitemap</a>
        					<br><a href="../terms.php">Terms and Conditions</a> 
                    </footer>
            </div>
        </div>

</body>
</html>
