<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" conent="{{ csrf_token() }}">

        <title>Deectionary</title>
        
        <link rel="stylesheet" href="/css/app.css">
        <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/d7a0ab4e52.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    </head>
    <body>
        <div id="root">
            
        </div>
        <script src="https://code.responsivevoice.org/responsivevoice.js"></script>
        <script src="/js/app.js"></script>
        <script>
            // To prevent the herokuapp idling
            setInterval(() => {
                axios.get('https://deectionary.herokuapp.com')
                    .then((res) => {

                    })
                    .catch((err) => {

                    });
            }, 300000);
        </script>
    </body>
</html>
