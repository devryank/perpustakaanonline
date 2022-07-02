<?php
  session_start();
  $book_isbn = $_GET['bookisbn'];
  // connecto database
  require_once "./functions/database_functions.php";
  $conn = db_connect();

  $query = "SELECT * FROM books WHERE book_isbn = '$book_isbn'";
  $result = mysqli_query($conn, $query);
  if(!$result){
    echo "Tidak dapat menampilkan data" . mysqli_error($conn);
    exit;
  }

  $row = mysqli_fetch_assoc($result);
  if(!$row){
    echo "Buku Kosong";
    exit;
  }

  $title = $row['book_title'];
  require "./template/header.php";
?>
      <!-- Example row of columns -->
      <p class="lead" style="margin: 25px 0"><a href="books.php">Buku</a> > <?php echo $row['book_title']; ?></p>
      <div class="row">
        <div class="col-md-3 text-center">
          <img class="img-responsive img-thumbnail" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
        </div>
        <div class="col-md-6">
          <h4>Deskripsi Buku</h4>
          <p><?php echo $row['book_descr']; ?></p>
          <h4>Detail Buku</h4>
          <table class="table">
          	<?php foreach($row as $key => $value){
              if($key == "book_descr" || $key == "book_image" || $key == "publisherid" || $key == "book_title"){
                continue;
              }
              switch($key){
                case "book_isbn":
                  $key = "ISBN";
                  break;
                case "book_title":
                  $key = "Judul";
                  break;
                case "book_author":
                  $key = "Penulis";
                  break;
                case "book_price":
                  $key = "Harga";
                  break;
              }
            ?>
            <tr>
              <td><?php echo $key; ?></td>
              <td><?php echo $value; ?></td>
            </tr>
            <?php 
              } 
              if(isset($conn)) {mysqli_close($conn); }
            ?>
          </table>
          <form method="post" action="cart.php">
            <input type="hidden" name="bookisbn" value="<?php echo $book_isbn;?>">
            <input type="submit" value="Beli / Tambah Keranjang" name="cart" class="btn btn-primary">
          </form>
       	</div>
      </div>
<?php
      
  
  require_once "./template/footer.php";
?>