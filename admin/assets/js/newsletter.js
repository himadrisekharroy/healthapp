checkPermission(window.sessionStorage.getItem("admin_id"), 20, 'view'); // admin_id, module_id, role_type
jQuery(document).ready(function() {	
$(".username").html(window.sessionStorage.getItem("admin_name"));
	$.post( api_url + "/newsletter.php", { func:"list"})
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>Email </th>';
			html +=		'			<th>Subscribed on</th>';
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			for(var key in data.data)
			{				
				html +=		'	<tr>';
				html +=		'		<td>'+ i +'</td>';
				html +=		'		<td>'+ data.data[key]['email'] +'</td>';
				html +=		'		<td>'+ data.data[key]['create_date'] +'</td>';			
				html +=		'	</tr>';
				i++;			
			}
			html +=		'	</tbody>';
			html +=		'</table>';
			console.log(data);
			$(".portlet-body").html(html).find(".change_status a").click(function(){
				var id = $(this).parent().attr('id').split("_");	
				$("#cs_"+id[1] +" a").html ('Please Wait');			
				$.post( api_url + "/sd_state.php", { func:"status_change", id:id[1]})
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
				$("#newsletter").addClass("active").addClass("open");
				if($("#newsletter span").hasClass("arrow"))
				{
					$("#newsletter span").addClass("open");	
				}
				$("#list_newsletter").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});		
			
		});
});

jQuery(document).on('click', '.edit_delete .edit_btn', function(e){
	var id = $(this).parent().attr('id').split("_");	
	window.location.href="add_state.html?id="+id[1];
});

jQuery(document).on('click', '.edit_delete .dlt_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	//alert(id);
	var r =confirm("Are you sure to delete!");
	if (r == true) {
    		$.post( api_url + "/sd_state.php", { func:"delete", id:id[1], admin_id: window.sessionStorage.getItem("admin_id")})
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