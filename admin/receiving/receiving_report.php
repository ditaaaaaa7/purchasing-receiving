
<div class="card card-outline card-danger">
	<div class="card-header">
		<h3 class="card-title">Receiving Reports</h3>
	</div>
	<div class="card-body">
            <form action="" method=""></form>
            <div class="container align-items-center">
                <form action="">
                    <div class="row">
                        <div class="col form-group">
                            <label for="inputBulan" class="font-weight-bold">Pilih Bulan :</label>
                            <select class="form-control " name="bln">
                                <option selected=""> </option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col form-group">
                            <label for="inputTahun" class="font-weight-bold">Pilih Tahun :</label>
                            <?php
                                $now=date('Y');
                                echo "<select name='thn' class='form-control'>";
                                echo "<option value='' selected></option>";
                                for ($a=2022;$a<=$now;$a++)
                                {
                                    echo "<option value='$a'>$a</option>";
                                }
                                echo "</select>";
                            ?>
                        </div>
                        <div class="col form-group">
                            <label for="supplier_id" class="font-weight-bold">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="custom-select select2">
                            <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                            <?php 
                            $supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
                            while($row=$supplier->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?> ><?php echo $row['name'] ?></option>
                            <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">Tampilkan</button>
                    <button type="submit" class="btn btn-danger">Print</button>
                </form>
            </div><hr>
            <div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="30%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Date Created</th>
                            <th>From</th>
                            <th>Supplier</th>
                            <th>Items</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT p.*, s.name as supplier FROM `purchase_order_list` p inner join supplier_list s on p.supplier_id = s.id WHERE p.status = 'Receive' order by p.`date_created` desc");
                        while($row = $qry->fetch_assoc()):
                            $row['items'] = $conn->query("SELECT count(item_id) as `items` FROM `po_items` where po_id = '{$row['id']}' ")->fetch_assoc()['items'];
                            $isPartiallyReceive = $row['status'] == 'Partially Receive';
                        ?>
                            <tr class="text-center">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['po_code'] ?></td>
                                <td><?php echo $row['supplier'] ?></td>
                                <td><?php echo number_format($row['items']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
		</div>
		</div>
	</div>
</div>

<script>
</script>