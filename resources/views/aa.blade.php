<!DOCTYPE html>
<html>
<head>
    <title>Category Details</title>
</head>
<body>
    <header>
        <h1>Category Details</h1>
    </header>
    <main>
        <h2>Category Information</h2>
        <ul>
            <li>ID: {{ $categories->id }}</li>
            <li>Name: {{ $categories->name }}</li>
            <li>Description: {{ $categories->description }}</li>
        </ul>
        <h2>QR Code</h2>
        <img src="{!!$message->embedData(QrCode::format('png')->generate('Embed me into an e-mail!'), 'QrCode.png', 'image/png')!!}">


    </main>
    <footer>
        <p>&copy; {{ date('Y') }} Your Company Name</p>
    </footer>
</body>
</html>
