<?php
	session_start();
	$_SESSION['err'] = 1;
	foreach($_POST as $key => $value){
		if(trim($value) == ''){
			$_SESSION['err'] = 0;
		}
		break;
	}

	if($_SESSION['err'] == 0){
		header("Location: checkout.php");
	} else {
		unset($_SESSION['err']);
	}


	$_SESSION['ship'] = array();
	foreach($_POST as $key => $value){
		if($key != "submit"){
			$_SESSION['ship'][$key] = $value;
		}
	}
	require_once "./functions/database_functions.php";
	// print out header here
	$title = "Purchase";
	require "./template/header.php";
	// connect database
	if(isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))){
?>
	<table class="table">
		<tr>
			<th>Item</th>
			<th>Harga</th>
	    	<th>Jumlah</th>
	    	<th>Total</th>
	    </tr>
	    	<?php
			    foreach($_SESSION['cart'] as $isbn => $qty){
					$conn = db_connect();
					$book = mysqli_fetch_assoc(getBookByIsbn($conn, $isbn));
			?>
		<tr>
			<td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
			<td><?php echo "Rp" . $book['book_price']; ?></td>
			<td><?php echo $qty; ?></td>
			<td><?php echo "Rp" . $qty * $book['book_price']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th><?php echo $_SESSION['total_items']; ?></th>
			<th><?php echo "Rp" . $_SESSION['total_price']; ?></th>
		</tr>
		<tr>
			<td>Ongkir</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>9000</td>
		</tr>
		<tr>
			<th>Total Pembelian</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th><?php echo "Rp" . ($_SESSION['total_price'] + 9000); ?></th>
		</tr>
	</table>
	<form method="post" action="process.php" class="form-horizontal">
		<?php if(isset($_SESSION['err']) && $_SESSION['err'] == 1){ ?>
		<p class="text-danger">All fields have to be filled</p>
		<?php } ?>
		<div class="form-group">
            <label for="card_owner" class="col-lg-2 control-label">Nama</label>
            <div class="col-lg-10">
              	<input type="text" class="form-control" name="card_owner">
            </div>
        </div>
        <div class="form-group">
            <label for="metode_pembayaran" class="col-lg-2 control-label">Metode Pembayaran</label>
            <div class="col-lg-10">
              	<select class="form-control" name="metode_pembayaran">
                  	<option value="Shopeepay">Shopeepay</option>
                  	<option value="Transferbank">Transfer Bank</option>
                  	<option value="AlfamartIndomaret">Alfamart/Indomaret</option>
                  	<option value="OVO">OVO</option>
              	</select>
            </div>
        </div>
        <div class="form-group">
            <label for="card_number" class="col-lg-2 control-label">Nomor Telepon</label>
            <div class="col-lg-10">
              	<input type="text" class="form-control" name="card_number">
            </div>
        </div>
        <div class="form-group">
            <label for="jasa_pengiriman" class="col-lg-2 control-label">Jasa Pengiriman</label>
            <div class="col-lg-10">
              	<select class="form-control" name="jasa_pengiriman">
                  	<option value="JNE">JNE</option>
                  	<option value="JNT">JNT</option>
                  	<option value="SiCepat">SiCepat</option>
                  	<option value="AnterAja">AnterAja</option>
              	</select>
        </div>
    </div>
        
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
              	<button type="reset" class="btn btn-default">Hapus</button>
              	<button type="submit" class="btn btn-primary">Beli</button>
              	<a href="books.php" class="btn btn-primary">Lanjut Pembelian</a>
            </div>
        </div>
    </form>
	<h5>Note : Klik tombol beli untuk konfirmasi pembelian anda, klik hapus untuk mengganti data atau lanjut belanja untuk tambah atau menghapus item.</h5>
<?php
	} else {
		echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
	}
    
  if(isset($conn)) { mysqli_close($conn); }
  require_once "./template/footer.php";
?>