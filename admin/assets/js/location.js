checkPermission(window.sessionStorage.getItem("admin_id"), 5, 'view'); // admin_id, module_id, role_type
jQuery(document).ready(function() {	

$(".username").html(window.sessionStorage.getItem("admin_name"));

	$.post( api_url + "/sd_city.php", { func:"active_list"})
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			for(var key in data.data)
			{
				$("#search_city_id").append(new Option(data.data[key]['city'], data.data[key]['city_id']));
			}
		})
	
	getlocationList("", "");
	$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#location").addClass("active").addClass("open");
				if($("#location span").hasClass("arrow"))
				{
					$("#location span").addClass("open");	
				}
				$("#list_location").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});
});

jQuery(document).on('click', '.edit_delete .edit_btn', function(e){
	var id = $(this).parent().attr('id').split("_");	
	window.location.href="add_location.html?id="+id[1];
});

jQuery(document).on('click', '.edit_delete .dlt_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	//alert(id);
	var r =confirm("Are you sure to delete!");
	if (r == true) {
    		$.post( api_url + "/sd_location.php", { func:"delete", id:id[1], admin_id: window.sessionStorage.getItem("admin_id")})
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

$("#search_btn").click(function(){
	
	var search_location_name = "";
	if($("#search_location_name").val()) search_location_name = $("#search_location_name").val();
	
	var search_city_id = "";
	if($("#search_city_id").val()) search_city_id = $("#search_city_id").val();
	
	getlocationList(search_location_name, search_city_id);
})

function getlocationList(search_location_name, search_city_id )
{
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	$.post( api_url + "/sd_location.php", { func:"list", search_location_name: search_location_name, search_city_id:search_city_id})
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>Location Name/ Pincode</th>';
			html +=		'			<th>City Name</th>';
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
				html +=		'		<td>'+ data.data[key]['location']+'<br/>'+ data.data[key]['location_pin_code']+'</td>';
				html +=		'		<td>'+  data.data[key]['city'] +'</td>';
				if(parseInt(data.data[key]['is_active']) == 1)
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['location_id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Active</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['location_id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Inactive</a></td>';
				
				html +=     '       <td class="edit_delete" id="ed_' + data.data[key]['location_id'] + '">';
				html +=     '       	<a href="javascript:void(0)" class="btn mini purple edit_btn"><i class="icon-edit"></i> Edit</a>';
				html +=     '           <a href="javascript:void(0)" class="btn mini red dlt_btn"><i class="icon-trash"></i> Delete</a>';
				html +=     '       </td>';
				html +=		'	</tr>';
				i++;			
			}
			html +=		'	</tbody>';
			html +=		'</table>';
			console.log(data);
			$(".list").html(html).find(".change_status a").click(function(){
				var id = $(this).parent().attr('id').split("_");	
				$("#cs_"+id[1] +" a").html ('Please Wait');			
				$.post( api_url + "/sd_location.php", { func:"status_change", id:id[1]})
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
		});
}