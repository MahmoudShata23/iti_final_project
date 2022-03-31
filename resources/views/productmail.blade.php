<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Product Added</title>
</head>
<body>
    <span>We have added a new product check this link to see the full details</span>
    <a href="http://localhost:4200/product-details/{{$id}}">Product Details</a>
    <h1>{{$name}}</h1>
    <h2>{{$price}} EGP</h2>
    <h3>{{$description}}</h3>
    <span>if you want to unsubscribe click here</span>
    <a href='http://localhost:4200/cancel-subscription'>Unsubscribe</a>
</body>
</html>