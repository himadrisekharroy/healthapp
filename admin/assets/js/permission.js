checkPermission(window.sessionStorage.getItem("admin_id"), 10, 'view'); // admin_id, module_id, role_type

jQuery(document).ready(function() {	
	$(".username").html(window.sessionStorage.getItem("admin_name"));
	$.post( api_url + "/admin_role.php", { func:"active_role_list"})
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			for(var key in data.data)
			{
				$("#search_role_id").append(new Option(data.data[key]['role_title'], data.data[key]['role_id']));
			}
		})	
	$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#role").addClass("active").addClass("open");
				if($("#location span").hasClass("arrow"))
				{
					$("#role span").addClass("open");	
				}
				$("#list_permission").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});
});

$("#search_btn").click(function(){
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	getPermissionList()
})

jQuery(document).on('click', '.edit_delete .edit_btn', function(e){
	var id = $(this).parent().attr('id').split("_");	
	window.location.href="add_location.html?id="+id[1];
});



function getPermissionList()
{
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	$.post( api_url + "/admin_role.php", { func:"permission", role_id: $("#search_role_id").val()})
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>Module Name</th>';
			html +=		'			<th>View Permission</th>';
			html +=		'			<th>Add Permission</th>';
			html +=		'			<th>Edit Permission</th>';
            html +=		'           <th>Delete Permission</th>';
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			for(var key in data.data.modules)
			{				
				html +=		'	<tr>';
				html +=		'		<td>'+ i +'</td>';
				html +=		'		<td>'+ data.data.modules[key]['name']+'</td>';
				
				if(parseInt(data.data.modules[key]['view']) == 1)
				html +=		'		<td class="change_status" id="cs_view_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Permitted</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_view_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Forbidden</a></td>';

				
				if(parseInt(data.data.modules[key]['add']) == 1)
				html +=		'		<td class="change_status" id="cs_add_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Permitted</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_add_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Forbidden</a></td>';
				
				if(parseInt(data.data.modules[key]['edit']) == 1)
				html +=		'		<td class="change_status" id="cs_edit_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Permitted</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_edit_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Forbidden</a></td>';

				if(parseInt(data.data.modules[key]['delete']) == 1)
				html +=		'		<td class="change_status" id="cs_delete_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Permitted</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_delete_' + data.data.modules[key]['id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Forbidden</a></td>';

				
				html +=		'	</tr>';
				i++;			
			}
			html +=		'	</tbody>';
			html +=		'</table>';
			//console.log(data);
			$(".list").html(html).find(".change_status a").click(function(){
				var id = $(this).parent().attr('id').split("_");
				var type = id[1] 	;
				$("#cs_"+type+"_"+id[2] +" a").html ('Please Wait');
				$(".btn").prop( "disabled", true );			
				$.post( api_url + "/admin_role.php", { func:"permission_change", type:type, module_id:id[2], role_id: $("#search_role_id").val()})
					.done(function(data){
						//alert(data);
						data = JSON.parse(data);
						//console.log(data);
						$(".btn").prop( "disabled", false );		
						if(data.success)
						{
							if(parseInt(data.status))
							{
								//alert("#cs_"+id[1]);								
								$("#cs_"+type+"_"+id[2] +" a").html ('<i class="icon-unlock"></i> Permitted').removeClass('blue').addClass('green');
							}
							else
							{ //alert("#cs_"+id[1]);
								$("#cs_"+type+"_"+id[2] +" a").html( '<i class="icon-lock"></i> Forbidden').removeClass('green').addClass('blue');;
							}
						}
									
					})	
							
				});
		});
}