checkPermission(window.sessionStorage.getItem("admin_id"), 17, 'view'); // admin_id, module_id, role_type

jQuery(document).ready(function() {
	$(".username").html(window.sessionStorage.getItem("admin_name"));
			
	$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#ecart").addClass("active").addClass("open");
				if($("#ecart span").hasClass("arrow"))
				{
					$("#ecart span").addClass("open");	
				}
				$("#list_ecart").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});	
			
	loadPage(0, 50, 1);
	
});

function loadPage(startingRow, countResult, pageCount)
{	
	var search_mobile  = $("#search_mobile").val();
	var search_shop_name  = $("#search_shop_name").val();
	var search_date_from  = $("#search_date_from").val();
	var search_date_to  = $("#search_date_to").val();

	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/sd_e_cart.php", { 
				func:"list", 
				starting:startingRow, 
				length:countResult, 
				page:pageCount,				
				search_mobile: search_mobile,
				search_shop_name: search_shop_name,
				search_date_from:search_date_from,
				search_date_to:search_date_to })
		.done(function(data){
			//alert(data);
			console.log(data);
			data = JSON.parse(data);			
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>Product</th>';
			html +=		'			<th>Vendor</th>';
			html +=		'			<th>User Mobile</th>';
			html +=		'			<th>Enquery on</th>';            
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			for(var key in data.data)
			{					
				html +=		'	<tr id="element_'+ data.data[key]['id'] +'">';
				html +=		'		<td>'+ i +'</td>';				
				html +=		'		<td><img src="https://s3.ap-southeast-1.amazonaws.com/sd-prod-101-img/'+ data.data[key]['product_image'] +'" width="100"><br/>"'+data.data[key]['product_name']+"<br/>Rs. "+data.data[key]['product_price']+" /-<br/>"+'</td>';
				html +=		'		<td><b>'+data.data[key]['shop_name']+'</b><br/>'+data.data[key]['vendor_fname']+' '+data.data[key]['vendor_lname']+'<br/><b>'+data.data[key]['vendor_mobile']+'</b></td>';
				html +=		'		<td>'+data.data[key]['user_mob_no']+'</td>';
				html +=		'		<td>'+data.data[key]['created_date']+'</td>';
								
				html +=		'	</tr>';			
				
				i++;			
			}
			var prevClass ="";
			if(data.currentPage == 1) prevClass = "disabled";
			
			var nextClass ="";
			if(data.currentPage == data.lastPage) nextClass = "disabled";
			
			var startingShow = parseInt(data.startingLimit)+1; 
			var endingShow = parseInt(data.startingLimit)+50;
			var totalShow = parseInt(data['total_count']);
			if( totalShow < endingShow) endingShow = totalShow;
			
			html +=		'	</tbody>';
			html +=		'</table>';
			html +=		'<div class="row-fluid"><div class="span6"><div class="dataTables_info" id="sample_editable_1_info">Showing '+startingShow+' to '+ endingShow +' of '+ totalShow +' entries</div></div><div class="span6"><div class="dataTables_paginate paging_bootstrap pagination"><ul><li class="prev '+prevClass+'"><a href="javascript:void(0)">← Previous</a></li><li class="active"><a href="javascript:void(0)" >'+data.currentPage+'</a></li><li class="next '+nextClass+'"><a href="javascript:void(0)">Next → </a></li></ul></div></div></div>';
			html +=		'<input type="hidden" id="current_page" value="'+data.currentPage+'"><input type="hidden" id="last_page" value="'+data.lastPage+'">';
			
			//console.log(data);			
			$(".list").html(html).find(".change_status a").click(function(){
				var id = $(this).parent().attr('id').split("_");	
				$("#cs_"+id[1] +" a").html ('Please Wait');			
				$.post( api_url + "/sd_billing.php", { func:"status_change", id:id[1]})
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

jQuery(document).on('click', '.prev a', function(e){
	if( parseInt($("#current_page").val()) > 1)
	{
		//alert("previous Click")
		var prevPage = parseInt($("#current_page").val()) - 1;
		var startingPoint = ((parseInt($("#current_page").val())- 2 ) * 50 ) ;
		loadPage(startingPoint, 50, prevPage);
	}
});

jQuery(document).on('click', '.next a', function(e){
	if( parseInt($("#current_page").val()) < parseInt($("#last_page").val()))
	{
		//alert("next Click");
		var nextPage = parseInt($("#current_page").val()) +1;
		var startingPoint = 50* parseInt($("#current_page").val()) ;
		//alert( nextPage +"===="+ startingPoint);
		loadPage(startingPoint, 50, nextPage);
	}
});

jQuery(document).on('click', '.edit_delete .edit_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	var currentPage = parseInt($("#current_page").val());
	var startingPoint = 50* parseInt($("#current_page").val()) ;	
	window.location.href="add_billing.html?id="+id[1]+"_"+currentPage+"_"+startingPoint;
});

jQuery(document).on('click', '.edit_delete .dlt_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	//alert(id);
	var r =confirm("Are you sure to delete!");
	if (r == true) {
		var currentPage = parseInt($("#current_page").val());
		var startingPoint = 50* parseInt($("#current_page").val()) ;	
		
    	$.post( api_url + "/sd_billing.php", { func:"delete", id:id[1], currentPage:currentPage, startingPoint:startingPoint, admin_id: window.sessionStorage.getItem("admin_id") })
			.done(function(data){
				//alert(data);
				data = JSON.parse(data);

				if(data.success)
				{
					//window.location.reload();
					$("#element_"+id[1]).remove();
				}
				else
				{
					alert("Vendor delete failed. please contact to Street delight admin.");
				}
			})
}
});

jQuery(document).on('change', '#search_city_id', function(){
	var search_city_id = $(this).val();
	
	$.post( api_url + "/sd_location.php", { func:"active_list_by_city", id:search_city_id })
		.done(function(data){
			//alert(data);
			data = JSON.parse(data);
			$('#search_location_id').find('option:not(:first)').remove();
			for(var key in data.data)
			{
				$("#search_location_id").append(new Option(data.data[key]['location_name'], data.data[key]['location_id']));
			}
		})

})


$("#search_btn").click(function()
{
	//alert($("#search_date_from").val() +"=="+$("#search_date_to").val());

	if($("#search_city_id").val() && $("#search_location_id").val() == "")
	{
		alert("Please select location.");
	}
	else if($("#search_date_from").val() == "" && $("#search_date_to").val())
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() && $("#search_date_to").val()=="")
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() == "" && $("#search_date_to").val() == "")
	{
		loadPage(0, 50, 1);	
	}
	else
	{
		var date1 = new Date($("#search_date_from").val());
		var date2 = new Date($("#search_date_to").val());
		var timeDiff = date2.getTime() - date1.getTime();
		
		if(timeDiff >= 0)
		{
			
			loadPage(0, 50, 1);		
		}
		else
		{
			alert("From date must be lower than to date.");		
		}
		
	}
	
	//search_date_form
	//search_date_to
		
	
})