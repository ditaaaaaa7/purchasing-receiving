<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
	<form action="" id="item-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="name" class="control-label">Name</label>
			<input type="text" name="name" id="name" class="form-control rounded-1" value="<?php echo isset($name) ? $name : ''; ?>">
		</div>
		<div class="form-group">
			<label for="unit" class="control-label">Unit</label>
			<select name="unit" id="unit" class="custom-select select2" required>
				<option value=""></option>
				<option value="pcs">pcs</option>
				<option value="pack">pack</option>
				<option value="can">can</option>
				<option value="kg">kg</option>
				<option value="ltr">ltr</option>
			</select>
		</div>
		<div class="form-group">
			<label for="type" class="control-label">Types of Item</label>
            <select name="type" id="type" class="custom-select select2">
				<option value=""></option>
				<option value="chemical">chemical</option>
				<option value="food">food</option>
				<option value="baverage">beverage</option>
				<option value="stationery">stationery</option>
				<option value="material">material</option>
            </select>
		</div>
		<div class="form-group">
			<label for="cost" class="control-label">Cost</label>
			<input type="number" name="cost" id="cost" class="form-control rounded-1" value="<?php echo isset($cost) ? formatRupiah($cost) : ''; ?>" required>
		</div>
	</form>
</div>
<script>
  
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Please Select here",width:"relative"})
		$('#item-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_item",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload();
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
	})
</script>