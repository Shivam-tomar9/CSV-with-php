<!DOCTYPE html>
<html>
<head>
    <title>CSV Import</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>CSV Import</h1>
        <form method="post" enctype="multipart/form-data" action="import.php">
            <div class="col-md-4">
                <label for="file">Select CSV file to import:</label>
                <input type="file" class="form-control" name="file" id="file">
                <br>
                <button type="submit" class="btn btn-primary" name="submit" value="Import">Import</button>
            </div>
        </form>
    </div>
</body>
</html>
