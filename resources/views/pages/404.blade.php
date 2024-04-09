<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        @font-face {
            font-family: "PoppinsBold";
            src: local("PoppinsBold"), url("/fonts/Poppins-Bold.ttf") format("truetype");
        }
        *{
            padding: 0;
            margin: 0;
            color: white;
            box-sizing: border-box;
        }
        body{
            background: black;
        }
        img{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            max-height: 100%;
            opacity: .2;
            z-index: -10;
            object-fit: cover;
        }
        .main{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100vh;
            flex-direction: column;
        }
        .code{
            font-size: 500px;
            line-height: 450px;
            font-family: PoppinsBold, sans-serif;
            opacity: .8;
            z-index: -100;
        }
        .title{
            font-size: 30px;
            font-family: PoppinsBold, sans-serif;
        }
        .subtitle{
            font-size: 14px;
            margin-top: 20px;
            font-family: PoppinsBold, sans-serif;
        }
        .btn{
            font-family: PoppinsBold, sans-serif;
            border-radius: 3px;
            background: #DADFE4;
            box-shadow: 0 6px 24px 0 rgba(0, 16, 41, 0.05);
            border: none;
            padding: 6px 16px;
            margin-top: 20px;
            cursor: pointer;
            font-size: 12px;
            color: #393939;
        }
    </style>
</head>
<body>
    <div class="main">
        <img src="/img/bg-404.png" alt="bg">
        <div class="code">404</div>
        <div class="title">Oops! This Page is Not Found.</div>
        <div class="subtitle">The requested page dose not exist</div>
        <a class="btn" href="https://mytripline.com/en">Back to Home</a>
    </div>
</body>
</html>