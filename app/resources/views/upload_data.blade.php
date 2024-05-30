<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Data Manual</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="antialiased">
    <div class="container">
        <h1>Upload Data to Dataset</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('dataset.upload', ['dataset' => $dataset->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea id="data" name="data" class="form-control" rows="30" cols="100" style="width: 950px; height: 180px;" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <div class="container">
            <p>The JSON to be uploaded must contain the following structure and names:</p>
            <pre style="background: lightgrey; width: 950px;">
                {
                    "data": [
                        {
                            "dataset_id": "X",
                            "latitude": X.XXX,
                            "longitude": X.XXXX,
                            "data": {
                                "Field1": "XXX.XXXX",
                                "Field2": "XXXXX",
                                "Field3": "XXXX"
                            }
                        },
                        {
                            "dataset_id": "X",
                            "latitude": X.XXX,
                            "longitude": X.XXXX,
                            "data": {
                                "Field1": "XXX.XXXX",
                                "Field2": "XXXXX",
                                "Field3": "XXXX"
                            }
                        }
                    ]
                }
            </pre>
        </div>
    </div>
</body>
</html>
