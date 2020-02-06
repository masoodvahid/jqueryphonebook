<?php 
	$uid = $_REQUEST['uid'];
	require('../config.php');
	$count = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM phones WHERE user_id = '$uid'"));
?>
<form method="POST" class="phones-list">
	<table class="dv-table phones-list-table" style="width:100%;padding:5px;margin-top:5px;">
		<?php
		if($count[0] > 0){
			$sql = mysql_query("SELECT * FROM phones WHERE user_id = '$uid'");
			while ($record = mysql_fetch_assoc($sql)){
				echo "
				<tr class='phone-record'>						
					<td width='85%'>						
						<input class='pid' type=hidden value='".$record['id']."'>
						<input class='phone-number' style='width:18%;' required value='".$record['phone']."'></input>
						<select  class='phone-type' panelHeight='auto' style='width: 15%'>
							<option value='mobile'";($record['phone_type'] == 'mobile')? print('selected') : ''; echo">موبایل</option>
							<option value='phone'"; ($record['phone_type'] == 'phone' )? print('selected') : ''; echo">تلفن</option>
							<option value='fax'";   ($record['phone_type'] == 'fax'   )? print('selected') : ''; echo">فکس</option>
						</select>
						<input name='note' class='phone-note' style='width:63%;' value='".$record['note']."'></input>
					</td>
					<td width='10%' style='text-align:left; vertical-align: middle'>
						<a href='#' class='btn btn-save'><img src='css/images/save-icon.png' width='24'></a>	
						<a href='#' class='btn btn-add'><img src='css/images/add-icon.png' width='24'></a>						
						<a href='#' class='btn btn-remove'><img src='css/images/remove-icon.png' width='24'></a>
					</td>
				</tr>
			  ";
			}
		}else{
			echo "
				<tr class='phone-record'>						
					<td width='85%'>						
						<input class='pid' type=hidden value=''>
						<input type='number' class='phone-number' style='width:18%;' required value=''></input>
						<select  class='phone-type' panelHeight='auto' style='width: 15%'>
							<option value='mobile'>موبایل</option>
							<option value='phone'>تلفن</option>
							<option value='fax'>فکس</option>
						</select>
						<input name='note' class='phone-note' style='width:63%;' value=''></input>
					</td>
					<td width='10%' style='text-align:center; vertical-align: middle'>
						<a href='#' class='btn btn-save'><img src='css/images/save-icon.png' width='24'></a>	
						<a href='#' class='btn btn-add'><img src='css/images/add-icon.png' width='24'></a>						
						<a href='#' class='btn btn-remove'><img src='css/images/remove-icon.png' width='24'></a>
					</td>
				</tr>
			";
		}
		?>	
	</table>	
</form>
<script type="text/javascript">
	$(".phones-list").on("click", ".btn-save", function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
		var uid = <?php echo $uid ?>;
		var pid = $(this).closest('tr').find('.pid').val();
		var phone = $(this).closest('tr').find('.phone-number').val();
		var type  = $(this).closest('tr').find('.phone-type').val();
		var note  = $(this).closest('tr').find('.phone-note').val();
		// console.log(pid);
		if ($.isEmptyObject(phone)){
			$.messager.alert('خطا', 'شماره تماس نمی تواند خالی باشد.', 'warning');
		}else{
			$.ajax({
				type: 'post',
				url: 'library/phones_operator.php',
				data: {uid: uid, pid: pid, phone: phone, type: type, note: note, opr:'edit'},
				dataType: 'json',
				success: function(response){
					if(response.Ok){
						$.messager.show({
							title: response.title,
							msg: response.msg
						});
					}else{
						$.messager.show({
							title: 'خطا',
							msg: response.msg
						});
					}
				},
				error: function(response){
					$.messager.show({
						title: 'خطا',
						msg: 'خطایی رخ داده است.'
					});				
				}
			});
		}
	})

	$(".phones-list").on("click", ".btn-remove", function(event){
		event.preventDefault();	
		event.stopImmediatePropagation();	
		var tr = $(this).closest('tr');
		var pid = tr.find('.pid').val();		
		if ($.isEmptyObject(pid)){
			tr.remove();
		}else{
			$.messager.confirm('حذف اطلاعات تماس','حذف اطلاعات غیرقابل بازگشت است. آیا اطمینان دارید ؟',function(r){
              if (r){
				$.ajax({
					type: 'post',
					url: 'library/phones_operator.php',
					data: {pid: pid, opr:'delete'},
					dataType: 'json',
					success: function(response){
						if(response.Ok){
							tr.remove();
							$.messager.show({
								title: 'بروزرسانی',
								msg: response.msg
							});
						}else{
							$.messager.show({
								title: 'خطا',
								msg: response.msg
							});
						}					
					},
					error: function(response){
						$.messager.show({
							title: 'خطا',
							msg: 'خطایی رخ داده است. لطفا صفحه با مجددا بارگزاری فرمایید'
						});				
					}
				});
			  }
			});
		}
	});
	
	$(".phones-list").on("click", ".btn-add", function(event){
		event.preventDefault();	
		event.stopImmediatePropagation();		
		var newRow = $(this).closest(".phones-list-table").find("tr:last").clone(true).find(':input').val('').end();
  		$(this).closest(".phones-list-table").append(newRow);
	});

	function addRow(){
		var meme = $(this).closest('.phones-list-table').find('.phone-number').val();
		console.log(meme);
		$(this).closest('.phones-list-table').append( 
			"<tr><td><input name='phone' class='easyui-textbox phone-number' style='width:18%;' required></input><select name='type' class='easyui-combobox phone-type' panelHeight='auto' style='width: 18%'><option value='mobile' selected>موبایل</option><option value='phone'>تلفن</option><option value='fax'>فکس</option></select><input name='note' class='easyui-textbox phone-note' style='width:60%;' value=''></input></td><td width='150' style='text-align:left'><button class='btn btn-remove'>حذف</buton><button class='btn btn-save'>ذخیره</button></td></tr>");		
	}
	
</script>
