checkPermission(window.sessionStorage.getItem("admin_id"), 4, 'view'); // admin_id, module_id, role_type
jQuery(document).ready(function() {
	
	$(".username").html(window.sessionStorage.getItem("admin_name"));
		
	$(".btn").prop( "disabled", true );
	$.post( api_url + "/sd_state.php", { func:"active_list"})
	.done(function(data){
		//alert(data);
		data = JSON.parse(data);

		for(var key in data.data)
		{
			$("#search_state_id").append(new Option(data.data[key]['state_name'], data.data[key]['state_id']));
		}
		
		getCityList("", "");		
	
		$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#city").addClass("active").addClass("open");
				if($("#city span").hasClass("arrow"))
				{
					$("#city span").addClass("open");	
				}
				$("#list_city").addClass("active");
				
				$(".btn").prop( "disabled", false );
				App.init(); // init the rest of plugins and elements
			});	
	});
});

jQuery(document).on('click', '.edit_delete .edit_btn', function(e){
	var id = $(this).parent().attr('id').split("_");	
	window.location.href="add_city.html?id="+id[1];
});

jQuery(document).on('click', '.edit_delete .dlt_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	//alert(id);
	var r =confirm("Are you sure to delete!");
	if (r == true) {
    		$.post( api_url + "/sd_city.php", { func:"delete", id:id[1],admin_id: window.sessionStorage.getItem("admin_id")})
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
	
	
	
	var searchedCityName = "";
	if($("#search_city_name").val()) searchedCityName = $("#search_city_name").val();
	
	var search_state_id = "";
	if($("#search_state_id").val()) search_state_id = $("#search_state_id").val();
	
	getCityList(searchedCityName, search_state_id);
})

function getCityList(searchedCityName, searchedStateId)
{
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/sd_city.php", { func:"list",searchedCityName:searchedCityName, searchedStateId:searchedStateId})
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>City Name</th>';
			html +=		'			<th>State Name</th>';
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
				html +=		'		<td>'+ data.data[key]['city'] +'</td>';
				html +=		'		<td>'+  data.data[key]['state_name'] +'</td>';
				if(parseInt(data.data[key]['is_active']) == 1)
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['city_id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Active</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['city_id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Inactive</a></td>';
				
				html +=     '       <td class="edit_delete" id="ed_' + data.data[key]['city_id'] + '">';
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
				$.post( api_url + "/sd_city.php", { func:"status_change", id:id[1]})
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