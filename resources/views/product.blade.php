<!DOCTYPE html>
<html>
    <head></head>
    <body>
        @foreach ($products as $product )
            <h2>{{$product->id}}</h2>
            <h2>{{$product->name}}</h2>
            <hr />
        @endforeach

    </body>
</html>