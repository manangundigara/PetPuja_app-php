<?php
$message = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password | PetPuja</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #fff5e1;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .email-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
            background-color: #fff5e1;
        }
        .container {
            max-width: 450px;
            background: #ffffff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #4e342e;
            position: relative;
            margin: auto;
        }
        
        @keyframes moveUp {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes moveDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(10px); }
        }

        .PetPuja span {
            display: inline-block;
            animation-duration: 1.5s;
            animation-iteration-count: infinite;
            animation-timing-function: ease-in-out;
        }

        .P, .t, .u { animation-name: moveUp; }
        .e, .P2, .j, .a { animation-name: moveDown; }

        .PetPuja {
            font-size: 50px;
            font-family: Arial, sans-serif;
            color: #ff7043;
            padding-top:15px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #d84315;
        }
        .tagline {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #ff7043;
        }
        .food-image {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #5d4037;
        }
        .password-container {
            font-size: 22px;
            font-weight: bold;
            background: linear-gradient(135deg, #ff7043, #d84315);
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .password-text {
            display: block;
            width: 100%;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            opacity: 0.9;
            font-weight: bold;
            color: #5d4037;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <p class="PetPuja">
                <span class="P">P</span>
                <span class="e">e</span>
                <span class="t">t</span>
                <span class="P2">P</span>
                <span class="u">u</span>
                <span class="j">j</span>
                <span class="a">a</span>
            </p>
            <h1 class="tagline">पहले पेट-पूजा, बाद में काम दूजा 🍽️</h1>
            <h1>Password Reset</h1>
           
            <img src="https://www.foodie.com/img/foodie-share-image-1280x720.png" alt="Delicious Food" class="food-image">
            <p>Your new password has been securely generated.</p>
            <div class="password-container">
                <span class="password-text">' . htmlspecialchars($password) . '</span>
            </div>
            <p class="footer">If you did not request this, you can safely ignore this email.</p>
        </div>
    </div>
</body>
</html>
';
?>
