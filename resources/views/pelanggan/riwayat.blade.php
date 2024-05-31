@extends('komponen.index')

@section('content')
<body>
		<div class="mytabs">
		    <input type="radio" id="tabfree" name="mytabs" checked="checked">
		    <label for="tabfree"><i class="fa fa-clock"></i><br>Belum Bayar</label>
		    <div class="tab">
		      <table id="table" class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Berat</th>
							<th>Total</th>
							<th>Opsi</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td> Gram</td>
							<td>Rp.</td>
							<td>
								<a href="" class="btn btn-info">Nota</a>
								<a href="" class= "btn btn-danger">Pembayaran</a>
								<a href="" class= "btn btn-warning">Batal</a>
							</td>
						</tr>

					</tbody>
				</table>
		    </div>

		    <input type="radio" id="tablg" name="mytabs">
		    <label for="tablg"><i class="fa fa-truck-loading"></i><br>Diproses</label>
		    <div class="tab">
		       <table id="table1" class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Berat</th>
							<th>Total</th>
							<th>Opsi</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td></td>
							<td></td>
							<td>Gram</td>
							<td>Rp</td>

							<td>
								<a href="" class="btn btn-info">Nota</a>
								<a href="" class= "btn btn-success">Bukti</a>
							</td>
						</tr>

					</tbody>
				</table>
		    </div>

		    <input type="radio" id="tabsilver" name="mytabs">
		    <label for="tabsilver"><i class="fa fa-shipping-fast"></i><br>Dikirim</label>
		    <div class="tab">
		      <table id="table2" class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Berat</th>
							<th>No Resi</th>
							<th>Total</th>
							<th>Opsi</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td></td>
							<td></td>
							<td>Gram</td>
							<td>

							</td>
							<td>Rp/td>
							<td>
								<a href="" class="btn btn-info">Nota</a>
								<a href="" class= "btn btn-success">Bukti</a>
							</td>
						</tr>

					</tbody>
				</table>
		    </div>

		    <input type="radio" id="tabgold" name="mytabs">
		    <label for="tabgold"><i class="fa fa-check"></i><br>Diterima</label>
		    <div class="tab">
		       <table id="table3" class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Berat</th>
							<th>No Resi</th>
							<th>Total</th>
							<th>Opsi</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td></td>
							<td></td>
							<td>Gram</td>
							<td>Rp. /td>
							<td>

							</td>
							<td>
								<a href="" class="btn btn-info">Nota</a>
								<a href="" class= "btn btn-success">Bukti</a>
							</td>
						</tr>

					</tbody>
				</table>
		    </div>


		  	<input type="radio" id="tabf" name="mytabs">
		    <label for="tabf"><i class="fa fa-times"></i><br>Batal</label>
		    <div class="tab">
		      <table id="table" class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Berat Barang</th>
							<th>Total</th>
							<th>Opsi</th>
						</tr>
					</thead>
					<tbody>

						<tr>
							<td></td>
							<td></td>
							<td> Gram</td>
							<td>Rp. </td>
							<td>
								<a href="" class="btn btn-info">Nota</a>
								<a href="" class="btn btn-danger">Dibatalkan</a>
							</td>
						</tr>

					</tbody>
				</table>
		    </div>
        </div>
<script src="admin/assets/js/jquery.min.js"></script>
<script src="admin/assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<style>
h2 {
    padding: 10px;
}
.mytabs {
    display: flex;
    flex-wrap: wrap;
    max-width: 1400px;
    margin: 50px auto;
    padding: 10px;
}
.mytabs input[type="radio"] {
    display: none;
}
.mytabs label {
    padding: 9px;
    color: #fff;
    background: #ff007f;
    font-weight: bold;
}

.mytabs .tab {
    width: 100%;
    padding: 4px;
    background: #fff;
    order: 1;
    display: none;
}
.mytabs input:hover + label {
	background: #3DC4B7;
	color:#ffffff;
	font-weight: normal;
}
.mytabs .tab h3 {
    font-size: 2em;
}

.mytabs input[type='radio']:checked + label + .tab {
    display: block;
}

.mytabs input[type="radio"]:checked + label {
    background: #000080;
}
</style>
    @endsection
