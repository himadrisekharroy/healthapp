checkPermission(window.sessionStorage.getItem("admin_id"), 8, 'view'); // admin_id, module_id, role_type

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
	
		
	$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#vendor").addClass("active").addClass("open");
				if($("#vendor span").hasClass("arrow"))
				{
					$("#vendor span").addClass("open");	
				}
				$("#list_vendor").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});	
			
	loadPage(0, 50, 1);
	
});

function loadPage(startingRow, countResult, pageCount)
{
	var search_city_id = $("#search_city_id").val();
	var search_location_id  = $("#search_location_id").val();
	var search_mobile  = $("#search_mobile").val();
	var search_shop_name  = $("#search_shop_name").val();
	
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/sd_vendors.php", { 
				func:"list", 
				starting:startingRow, 
				length:countResult, 
				page:pageCount,
				search_city_id: search_city_id, 
				search_location_id: search_location_id, 
				search_mobile: search_mobile,
				search_shop_name: search_shop_name })
		.done(function(data){
			//alert(data);
			//console.log(data);
			data = JSON.parse(data);			
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>&nbsp;</th>';
			html +=		'			<th>Name</th>';
			html +=		'			<th width="25%">Shop</th>';
			html +=		'			<th>Billing Details</th>';
			html +=		'			<th>Created On</th>';
            html +=		'           <th width="10%">Status</th>';
            html +=		'           <th width="10%">&nbsp;</th>';
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			for(var key in data.data)
			{	
				var vf = "";
				if(data.data[key]['vendor_fname']) vf = "-" + data.data[key]['vendor_fname'].trim().split(' ').join('-').toLowerCase();
				var vl = "";
				if(data.data[key]['vendor_lname']) vl = "-" + data.data[key]['vendor_lname'].trim().split(' ').join('-').toLowerCase();
				
				var ec = data.data[key]['vendor_id'] +vf + vl;

				var shop_img = 	'<img src="assets/img/no-shop.png" style="width:100px;"/>';		
				if(data.data[key]['photo_name']) shop_img = 	'<img src="https://s3.ap-southeast-1.amazonaws.com/sd-prod-101-img/'+data.data[key]['photo_name']+'" style="width:100px;"/>';		
				
				html +=		'	<tr id="element_'+ data.data[key]['vendor_id'] +'">';
				html +=		'		<td>'+ i +'</td>';
				html +=		'		<td>'+shop_img+'</td>';
				html +=		'		<td>'+data.data[key]['vendor_fname']+' '+data.data[key]['vendor_lname']+'<br/>('+data.data[key]['vendor_mobile']+')';
				html +=		'		<br/><a href="'+site_url+'enquiry-cart/'+ ec +'" class="btn mini yellow " target="_blank"><i class="icon-eye-open"></i> View Merchant Page</a><br/> </td>';
				
				html +=		'		<td>'+data.data[key]['shop_name']+'<br/>'+data.data[key]['shop_address1']+'<br/>'+data.data[key]['shop_address2']+'<br/><b>'+data.data[key]['location']+', '+data.data[key]['city']+'</></td>';
				html +=		'		<td> '+data.data[key]['billing']+' </td>';
				html +=		'		<td>'+data.data[key]['created_on']+'</td>';
				
				
				if(parseInt(data.data[key]['is_active']) == 1)
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['vendor_id'] + '"><a href="javascript:void(0);" class="btn mini green" title="Change Status"><i class="icon-unlock"></i> Active</a></td>';
				else
				html +=		'		<td class="change_status" id="cs_' + data.data[key]['vendor_id'] + '"><a href="javascript:void(0);" class="btn mini blue" title="Change Status"><i class="icon-lock"></i> Inactive</a></td>';
				
				html +=     '       <td class="edit_delete" id="ed_' + data.data[key]['vendor_id'] + '">';
				html +=     '       	<a href="javascript:void(0)" class="btn mini yellow edit_btn"><i class="icon-eye-open"></i> View</a><br/>';
				html +=     '       	<a href="javascript:void(0)" class="btn mini purple edit_btn"><i class="icon-edit"></i> Edit</a><br/>';
				html +=     '           <a href="javascript:void(0)" class="btn mini red dlt_btn"><i class="icon-trash"></i> Delete</a>';
				html +=     '           <a href="javascript:void(0)" class="btn mini black billing_btn"><i class="icon-money"></i> Billing</a>';
				html +=     '       </td>';
				html +=		'	</tr>';			
				
				i++;			
			}
			if(parseInt(data.total_count)>0)
			{			
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
			html +=		'<div class="row-fluid"><div class="span6"><div class="dataTables_info" id="sample_editable_1_info">Showing '+startingShow+' to '+ endingShow +' of '+ totalShow +' entries</div></div>';
			html +=		'<div class="span6"><div class="dataTables_paginate paging_bootstrap pagination"><ul>';
			html +=		'<li class="prev '+prevClass+'"><a href="javascript:void(0)">← Previous</a></li>';
			if((parseInt(data.currentPage)-2) >0 )
				html +=		'<li class="go_to" id="go_to_'+(parseInt(data.currentPage)-2)+'"><a href="javascript:void(0)" >'+(parseInt(data.currentPage)-2)+'</a></li>';
			if((parseInt(data.currentPage)-1) >0 )
				html +=		'<li class="go_to" id="go_to_'+(parseInt(data.currentPage)-1)+'"><a href="javascript:void(0)" >'+(parseInt(data.currentPage)-1)+'</a></li>';

			html +=		'<li class="active"><a href="javascript:void(0)" >'+data.currentPage+'</a></li>';

			if((parseInt(data.currentPage)+1) < parseInt(data.lastPage) )
				html +=		'<li class="go_to" id="go_to_'+(parseInt(data.currentPage)+1)+'"><a href="javascript:void(0)" >'+(parseInt(data.currentPage)+1)+'</a></li>';
			if((parseInt(data.currentPage)+2) < parseInt(data.lastPage) )
				html +=		'<li class="go_to" id="go_to_'+(parseInt(data.currentPage)+2)+'"><a href="javascript:void(0)" >'+(parseInt(data.currentPage)+2)+'</a></li>';

			html +=		'<li class="next '+nextClass+'"><a href="javascript:void(0)">Next → </a></li>';
			html +=		'</ul></div></div></div>';
			html +=		'<input type="hidden" id="current_page" value="'+data.currentPage+'"><input type="hidden" id="last_page" value="'+data.lastPage+'">';
			
			console.log(data);
			}
			else
			{
				html += '<tr><th colspan="8">No record to display...</th></tr>'
			}
			$(".list").html(html).find(".change_status a").click(function(){
				var id = $(this).parent().attr('id').split("_");	
				$("#cs_"+id[1] +" a").html ('Please Wait');			
				$.post( api_url + "/sd_vendors.php", { func:"status_change", id:id[1]})
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
jQuery(document).on('click', '.go_to a', function(e){
	var go_to_page = $(this).parent().attr('id').split("_");
	go_to_page = parseInt(go_to_page[2]);
	var startingPoint = (go_to_page - 1) * 50 ;
	loadPage(startingPoint, 50, go_to_page);
})
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
	window.location.href="add_vendor.html?id="+id[1]+"_"+currentPage+"_"+startingPoint;
});

jQuery(document).on('click', '.edit_delete .billing_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	var currentPage = parseInt($("#current_page").val());
	var startingPoint = 50* parseInt($("#current_page").val()) ;	
	window.location.href="add_billing.html?vid="+id[1]+"_"+currentPage+"_"+startingPoint;
});


jQuery(document).on('click', '.edit_delete .dlt_btn', function(e){
	var id = $(this).parent().attr('id').split("_");
	//alert(id);
	var r =confirm("Are you sure to delete!");
	if (r == true) {
		var currentPage = parseInt($("#current_page").val());
		var startingPoint = 50* parseInt($("#current_page").val()) ;	
		
    	$.post( api_url + "/sd_vendors.php", { func:"delete", id:id[1], currentPage:currentPage, startingPoint:startingPoint, admin_id: window.sessionStorage.getItem("admin_id") })
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


$("#search_btn").click(function(){
	
	var search_city_id = $("#search_city_id").val();
	var search_location_id  = $("#search_location_id").val();
	if(search_city_id && !search_location_id) alert("Please select a location.");
	else
	loadPage(0, 50, 1);
	//search_date_form
	//search_date_to	
	
})