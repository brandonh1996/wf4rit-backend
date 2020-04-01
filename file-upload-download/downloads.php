<?php include 'filesLogic.php';?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="style.css">
  <title>Download files</title>
</head>
<body>

    <h1><a href="index.php">Upload Files</a></h1>

<table>
<thead>
    <th>ID</th>
    <th>Creation Date</th>
    <th>isSigned</th>
    <th>Last Modified</th>
    <th>Task ID</th>
    <th>Filename</th>
    <th>Action</th>
</thead>
<tbody>
  <?php foreach ($files as $file): ?>
    <tr>
      <td><?php echo $file['documentID']; ?></td>
      <td><?php echo $file['creationDate']; ?></td>
      <td><?php echo $file['isSigned']; ?></td>
      <td><?php echo $file['lastModified']; ?></td>
      <td><?php echo $file['taskID']; ?></td>
      <td><?php echo $file['pdf_file']; ?></td>
      <td><a href="downloads.php?file_id=<?php echo $file['documentID'] ?>">Download</a></td>
    </tr>
  <?php endforeach;?>

</tbody>
</table>

</body>
</html>