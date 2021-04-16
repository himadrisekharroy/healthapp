checkPermission(window.sessionStorage.getItem("admin_id"), 9, 'view'); // admin_id, module_id, role_type
jQuery(document).ready(function() {	
$(".username").html(window.sessionStorage.getItem("admin_name"));
	$.post( api_url + "/user.php", { func:"list"})
		.done(function(data){
			console.log(data)
			data = JSON.parse(data);			
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>Image</th>';
			html +=		'			<th>Name</th>';
			html +=		'			<th>Email</th>';
			html +=		'			<th>Mobile</th>';
			html +=		'			<th>DOB</th>';
			html +=		'			<th>Status</th>';
            html +=		'           <th>&nbsp;</th>';
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			for(var key in data.data)
			{				
				html +=		'	<tr>';
				html +=		'		<td>'+ i +'</td>';
				html +=		'		<td><img src="'+site_url+"images/user_images/"+ data.data[key]['image']+'" style="width:30px;"/></td>';
				html +=		'		<td>'+ data.data[key]['f_name']+" "+data.data[key]['l_name']+'( '+ data.data[key]['sex']+' )</td>';
				html +=		'		<td>'+ data.data[key]['email_id']+'</td>';
				html +=		'		<td>'+ data.data[key]['mobile']+'</td>';
				html +=		'		<td>'+ data.data[key]['dob']+'</td>';
				if(parseInt(data.data[key]['is_active']) == 1)
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Active</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Inactive</a></td>';
				
				html +=     '       <td class="edit_delete" id="ed_' + data.data[key]['id'] + '">';
				html +=     '       	<a href="javascript:void(0)" class="btn mini purple edit_btn"><i class="icon-edit"></i> Edit</a>';
				html +=     '           <a href="javascript:void(0)" class="btn mini red dlt_btn"><i class="icon-trash"></i> Delete</a>';
				html +=     '       </td>';
				html +=		'	</tr>';
				
				i++;			
			}
			html +=		'	</tbody>';
			html +=		'</table>';
			console.log(data);
			$(".portlet-body").html(html).find(".change_status a").click(function(){
				var id = $(this).parent().attr('id').split("_");	
				$("#cs_"+id[1] +" a").html ('Please Wait');			
				$.post( api_url + "/user.php", { func:"status_change", id:id[1]})
					.done(function(data){
						//alert(data);
						data = JSON.parse(data);
						if(data.success)
						{
							if(parseInt(data.status))
							{
								//alert("#cs_"+id[1]);								
								$("#cs_"+id[1] +" a").html ('<i class="icon-unlock"></i> Active').removeClass('blue').addClass('green');
							}
							else
							{ //alert("#cs_"+id[1]);
								$("#cs_"+id[1] +" a").html( '<i class="icon-lock"></i> Inactive').removeClass('green').addClass('blue');;
							}
						}
									
					})	
							
				});
			$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#user").addClass("active").addClass("open");
				if($("#user span").hasClass("arrow"))
				{
					$("#user span").addClass("open");	
				}
				$("#list_user").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});		
			
		});
});

jQuery(document).on('click', '.edit_delete .edit_btn', function(e){
	var id = $(this).parent().attr('id').split("_");	
	window.location.href="add_user.html?id="+id[1];
});

jQuery(document).on('click', '.edit_delete .dlt_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	//alert(id);
	var r =confirm("Are you sure to delete!");
	if (r == true) {
    		$.post( api_url + "/user.php", { func:"delete", id:id[1], admin_id: window.sessionStorage.getItem("admin_id")})
			.done(function(data){
				data = JSON.parse(data);
				if(data.success)
				{
					window.location.reload();
				}
				else
				{
					alert("fail");
				}
			})
}
});