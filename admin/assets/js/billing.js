checkPermission(window.sessionStorage.getItem("admin_id"), 16, 'view'); // admin_id, module_id, role_type

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
				$("#billing").addClass("active").addClass("open");
				if($("#billing span").hasClass("arrow"))
				{
					$("#billing span").addClass("open");	
				}
				$("#list_billing").addClass("active");
				
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
	var search_date_from  = $("#search_date_from").val();
	var search_date_to  = $("#search_date_to").val();

	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/sd_billing.php", { 
				func:"list", 
				starting:startingRow, 
				length:countResult, 
				page:pageCount,
				search_city_id: search_city_id, 
				search_location_id: search_location_id, 
				search_mobile: search_mobile,
				search_shop_name: search_shop_name,
				search_date_from:search_date_from,
				search_date_to:search_date_to })
		.done(function(data){
			//alert(data);
			//console.log(data);
			data = JSON.parse(data);			
			var html = 	'<table class="table table-striped table-hover">';
			html +=		'	<thead>';
			html +=		'		<tr>';
			html +=		'			<th>#</th>';
			html +=		'			<th>Vendor</th>';
			html +=		'			<th>Plan</th>';
			html +=		'			<th>Payment</th>';
			html +=		'			<th>Payment Cycle</th>';
			html +=		'			<th>Validity</th>';
			html +=		'			<th>Entry on</th>';            
            html +=		'           <th width="10%">&nbsp;</th>';
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			for(var key in data.data)
			{					
				html +=		'	<tr id="element_'+ data.data[key]['bill_id'] +'">';
				html +=		'		<td>'+ i +'</td>';
				html +=		'		<td><b>'+data.data[key]['shop_name']+'</b><br/>'+data.data[key]['vendor_fname']+' '+data.data[key]['vendor_lname']+'<br/><b>'+data.data[key]['vendor_mobile']+'</b></td>';
				html +=		'		<td>'+data.data[key]['plan_name']+'</td>';
				html +=		'		<td> Rs.'+data.data[key]['amount']+'/- ( '+data.data[key]['method_name']+' ) </td>';
				html +=		'		<td>'+data.data[key]['billing_cycle_name']+'</td>';
				html +=		'		<td>'+data.data[key]['validity']+'</td>';
				html +=		'		<td>'+data.data[key]['created_date']+'</td>';
												
				html +=     '       <td class="edit_delete" id="ed_' + data.data[key]['bill_id'] + '">';
				html +=     '<input type="hidden" id="invoice_id_'+ data.data[key]['bill_id'] +'" value="'+ data.data[key]['invoice_id'] +'">';
				if(data.data[key]['invoice_id'] == 0)
				{
				html +=     '       	<a href="javascript:void(0)" class="btn mini purple edit_btn" id="edit_btn_'+data.data[key]['bill_id']+'"><i class="icon-edit"></i> Edit</a><br/>';
				}
				html +=     '           <a href="javascript:void(0)" class="btn mini black billing_btn"><i class="icon-print"></i> Invoice</a>';
				html +=     '       </td>';
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
jQuery(document).on('click', '.edit_delete .billing_btn', function(e){
	var bill_id = $(this).parent().attr('id').split("_");
	bill_id = bill_id[1];
	if(parseInt($("#invoice_id_" + bill_id).val()) == 0)
	{
		openbox('dddd');

		$.post( api_url + "/sd_billing.php", { func:"get_data_for_invoice", id:bill_id })
		.done(function(data){
			
			data = JSON.parse(data);
			$("#merchant_name").val(data.data.vendor_fname+" "+ data.data.vendor_lname);
			$("#merchant_mobile").val(data.data.vendor_mobile);
			$("#merchant_location").val(data.data.location+", "+data.data.city);
			$("#gst").val(data.data.gst);
			$("#date_section").html(data.data.created_date);
			
			$("#desc_section").html(data.data.description);
			$("#amt_section").html("Rs."+data.data.amount+"/-");
			$("#total_section").html("Rs."+data.data.amount+"/-");
			var planHtml= "";
			for(var key in data.data.plan)
			{
				var check = "";
				if(data.data.plan_id ==  data.data.plan[key].id) check="checked";
				planHtml += "<input type='checkbox' readonly "+check+"> "+data.data.plan[key].plan_name +"<br/>";
			}
			$("#plan_section").html(planHtml);
			$("#lb_bill_id").val(bill_id);
			$("#lb_plan_id").val(data.data.plan_id);
			
			$("#invoice_id").html(data.data.visible_invoice_id);

			if(parseInt(data.data.invoice_id) > 0 )
			{
				//alert(parseInt(data.data.invoice_id));
				$("#generate_invoice").hide(); 
				$("#print_invoice").show();
			}
			else
			{
				$("#generate_invoice").show();
				$("#print_invoice").hide();
			}
		});
	}
	else
	{
		window.open(
		  'create_invoice.html?invoice='+$("#invoice_id_" + bill_id).val(),
		  '_blank' // <- This is what makes it open in a new window.
		);
		
	}
});

$("#generate_invoice").click(function(){
	$("#plz_wait").hide();
	var x = confirm("Are you sure to generate Invoice? You will not able to edit the billing information!!");
	if(x)
	{
		$("#plz_wait").show();
		$("#generate_invoice").hide();
		$("#print_invoice").hide();
		$.post( api_url + "/sd_invoice.php", 
		{ 
			func:"generate_invoice", 
			merchant_name:$("#merchant_name").val(),
			merchant_mobile:$("#merchant_mobile").val(),
			merchant_location:$("#merchant_location").val(),
			bill_id:$("#lb_bill_id").val(),
			bill_date:$("#date_section").html(),
			description:$("#desc_section").html(),
			amount:$("#amt_section").html(),
			plan_id:$("#lb_plan_id").val()
		})
		.done(function(data){
			$("#plz_wait").hide();
			//alert(data);
			data = JSON.parse(data);
			if(data.success)
			{
				var bill_id = $("#lb_bill_id").val();
				$("#invoice_id_"+bill_id).val(data.invoice_id);
				$('#edit_btn_'+bill_id).remove();
				$("#generate_invoice").hide();
				$("#print_invoice").show();	

				$("#succ_msg").show().html(data.msg);
				$("#err_msg").hide().html("");	
			}
			else
			{
				$("#succ_msg").show().html("");
				$("#err_msg").hide().html(data.msg);	
			}
			
		})

		
	}

})

$("#print_invoice").click(function(){

	var bill_id = $("#lb_bill_id").val();
	window.open(
		  'create_invoice.html?invoice='+$("#invoice_id_" + bill_id).val(),
		  '_blank' // <- This is what makes it open in a new window.
		);
})