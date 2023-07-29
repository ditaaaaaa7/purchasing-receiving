<div class="card card-outline card-danger">
	<div class="card-header">
		<h3 class="card-title">List of Purchase Requests</h3>
        <div class="card-tools">
			<a href="<?php echo base_url ?>admin/?page=purchase_order/manage_po" class="btn btn-danger btn-md"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="4%">
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                        <col width="6%">
                        <col width="10%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="text-center">
                            <th >#</th>
                            <th>Date Created</th>
                            <th>PO Code</th>
                            <th>Supplier</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT p.*, s.name as supplier FROM `purchase_order_list` p inner join supplier_list s on p.supplier_id = s.id WHERE p.status='Pending' order by p.`date_created` desc");
                        while($row = $qry->fetch_assoc()):
                            $row['items'] = $conn->query("SELECT count(item_id) as `items` FROM `po_items` where po_id = '{$row['id']}' ")->fetch_assoc()['items'];
                        ?>
                            <tr class="text-center">
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d",strtotime($row['date_created'])) ?></td>
                                <td><?php echo $row['po_code'] ?></td>
                                <td><?php echo $row['supplier'] ?></td>
                                <td><?php echo number_format($row['items']) ?></td>
                                <td>
                                    <?php
                                        $status = $row['status'];

                                        switch ($status) {
                                            case 'Process':
                                                $label = 'Process';
                                                $badgeClass = 'badge-primary';
                                                break;
                                            case 'Partially Receive':
                                                $label = 'Partially receive';
                                                $badgeClass = 'badge-warning';
                                                break;
                                            case 'Receive':
                                                $label = 'Received';
                                                $badgeClass = 'badge-success';
                                                break;
                                            default:
                                                $label = 'N/A';
                                                $badgeClass = 'badge-danger';
                                        }

                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> rounded-pill"><?php echo $label; ?></span>
                                </td>
                                <td align="center">
                                    <div class="card-body">
                                    <a type="button" class="btn btn-success btn-md" href="<?php echo base_url.'admin?page=receiving/manage_receiving&po_id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-boxes" data-toggle="tooltip" data-placement="top" title="Receive"></span> 
                                    </a>
                                    <a type="button" class="btn btn-dark btn-md" href="<?php echo base_url.'admin?page=purchase_order/manage_po&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit"></span>
                                    </a>
                                    <a type="button" class="btn btn-info btn-md" href="<?php echo base_url.'admin?page=purchase_order/view_po&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View"></span>
                                    </a>
                                    <a type="button" class="btn btn-danger btn-md" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></span>
                                    </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
		</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Purchase Request permanently?","delete_po",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Payment Details","transaction/view_payment.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_po($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_po",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Report Purchase Request Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-red")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                    //   '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Purchase Request</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
            var _c = $('list').html();
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);
        })
    })
    $(function () {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>