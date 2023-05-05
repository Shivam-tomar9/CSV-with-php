<?php
require "db_connection.php";

$sql = "SELECT depart_menu FROM departments";
$result = $conn->query($sql);
$i=0;
$categories = array();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $categories[] = $row['depart_menu'];
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category = $_POST['category'];

  $sql = "SELECT size FROM sizes WHERE category_id = (SELECT id FROM departments WHERE depart_menu = ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $category);
  $stmt->execute();
  $result = $stmt->get_result();

  $sizes = array();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $sizes[] = $row['size'];
    }
  }

  $filename = $category . '_sizes.csv';
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="' . $filename . '";');

  $output = fopen('php://output', 'w');
  $header = array('ori_part_no', 'part_no', 'TOTAL QTY', 'TOTAL AMT');
  foreach ($sizes as $size) {
    $header[] = "size"." ".$size;
  }
  $count=count($sizes);
  fputcsv($output, $header);
  
  foreach ($sizes as $size) {
    $data=array('12345', '67890', '15', '1500');
    for($j=0;$j<$count;$j++)
    {
      array_push($data,"3");
    }
    $data[] = 'QTY';
    fputcsv($output, $data);
   
  $data = array('','','','');
  for($j=0;$j<$count;$j++)
  {
    array_push($data,"10");
  }
  $data[] = 'PRICE';
  fputcsv($output, $data);

  $data = array();
  fputcsv($output, $data);
}
  

  
  fclose($output);
  exit;
  
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Select Category</title>
  </head>
  <body>
    <div class="container">
    <form id="generate-csv-form">
        <div class="form-group">
            <div class="col-md-4">
      <label for="category">Select a category:</label>
      <select id="category" name="category" class="form-control" style="font-size: 16px; padding: 5px;">
        <?php foreach ($categories as $category) { ?>
          <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
        <?php } ?>
      </select>
        
      <button class="btn btn-primary" type="submit">Generate CSV</button>
        </div>
        </div>
    </form>
        </div>

    <script>
      const generateCSV = (category) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'selectcat.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.responseType = 'blob';
        xhr.onload = () => {
          if (xhr.status === 200) {
            const url = URL.createObjectURL(xhr.response);
            const link = document.createElement('a');
            link.href = url;
            link.download = `${category}_sizes.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
          }
        };
        xhr.send(`category=${encodeURIComponent(category)}`);
      };

      const form = document.querySelector('#generate-csv-form');
      form.addEventListener('submit', (event) => {
        event.preventDefault();
        const category = document.querySelector('#category').value;
        generateCSV(category);
      });
    </script>
  </body>
</html>
