checkPermission(window.sessionStorage.getItem("admin_id"), 18, 'view'); // admin_id, module_id, role_type

jQuery(document).ready(function() {
	$(".username").html(window.sessionStorage.getItem("admin_name"));
		
		
	$(".page-sidebar").load("nav.html",function(response, status){
				$(".page-sidebar li").removeClass("active");
				if($(".page-sidebar li span").hasClass("arrow"))
				{
					$(".page-sidebar li span").removeClass("open");
				}
				$("#report").addClass("active").addClass("open");
				if($("#report span").hasClass("arrow"))
				{
					$("#report span").addClass("open");	
				}
				$("#list_report").addClass("active");
				
				App.init(); // init the rest of plugins and elements
			});	
	
});

function loadPageMsg()
{
	var search_date_from  = $("#search_date_from").val();
	var search_date_to  = $("#search_date_to").val();
	$(".btn").hide();
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/report.php", { 
				func:"msg_list", 				
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
			html +=		'			<th>Bill ID</th>';
			html +=		'			<th>User</th>';
			html +=		'			<th>Date</th>';			
			html +=		'			<th>Validity</th>';			
			html +=		'			<th style="text-align:right;">Amount</th>';            
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			var total = 0;
			for(var key in data.data)
			{					
				html +=		'	<tr id="element_'+ data.data[key]['bill_id'] +'">';
				html +=		'		<td>'+ i +'</td>';	
				html +=		'		<td>'+data.data[key]['bill_id']+'</td>';
				html +=		'		<td><b>'+data.data[key]['f_name']+' '+data.data[key]['l_name']+'<br/><b>'+data.data[key]['mobile']+'</b>'+'<br/><b>'+data.data[key]['email_id']+'</b></td>';
				html +=		'		<td>'+data.data[key]['create_date']+'</td>';
				html +=		'		<td>'+data.data[key]['validity']+' Days</td>';
				html +=		'		<td style="text-align:right;"> Rs. '+data.data[key]['amount']+'/-  </td>';								
				html +=		'	</tr>';			
				
				i++;

				total = total + parseInt(data.data[key]['amount']);			
			}		
			
			html +=		'	<tr><td colspan="5" style="text-align:right;"><b>Total:</b></td> <td style="text-align:right"><b>Rs. '+total+'/- </b></td></tr>'
			html +=		'	</tbody>';
			html +=		'</table>';
			
			console.log(data);	
			$(".list").html(html);

			$("#r_title").html('<i class="icon-comments"></i> Report showing from '+search_date_from+" to "+search_date_to);
			$("#download_btn").removeClass('hide');
			$(".btn").show();
		});
}

function loadPageAppDate()
{
	var search_date_from  = $("#search_date_from").val();
	var search_date_to  = $("#search_date_to").val();
	$(".btn").hide();
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/report.php", { 
				func:"app_date_list", 				
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
			html +=		'			<th>Bill ID</th>';
			html +=		'			<th>Doctor</th>';
			html +=		'			<th>User</th>';
			html +=		'			<th>App. Date</th>';	
			html +=		'			<th>Created On</th>';			
			html +=		'			<th>Percentage</th>';			
			html +=		'			<th style="text-align:right;">Amount</th>';            
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			var total = 0;
			for(var key in data.data)
			{					
				html +=		'	<tr id="element_'+ data.data[key]['id'] +'">';
				html +=		'		<td>'+ i +'</td>';	
				html +=		'		<td>'+data.data[key]['id']+'</td>';
				html +=		'		<td><b>'+data.data[key]['doc_name']+'</b></td>';
				html +=		'		<td><b>'+data.data[key]['user_name']+'</b></td>';
				html +=		'		<td>'+data.data[key]['app_date']+'</td>';
				html +=		'		<td>'+data.data[key]['create_on']+'</td>';
				html +=		'		<td>'+data.data[key]['per_visit_change']+' % '+data.data[key]['actora_percentage']+' </td>';
				html +=		'		<td style="text-align:right;"> Rs. '+data.data[key]['amount']+'/-  </td>';								
				html +=		'	</tr>';			
				
				i++;

				total = total + parseInt(data.data[key]['amount']);			
			}		
			
			html +=		'	<tr><td colspan="7" style="text-align:right;"><b>Total:</b></td> <td style="text-align:right"><b>Rs. '+total+'/- </b></td></tr>'
			html +=		'	</tbody>';
			html +=		'</table>';
			
			console.log(data);	
			$(".list").html(html);

			$("#r_title").html('<i class="icon-comments"></i> Report showing from '+search_date_from+" to "+search_date_to);
			$("#download_btn").removeClass('hide');
			$(".btn").show();
		});
}


function loadPageCreateDate()
{
	var search_date_from  = $("#search_date_from").val();
	var search_date_to  = $("#search_date_to").val();
	$(".btn").hide();
	$(".list").html('<div style="width:100%; text-align:center;"><img src="assets/img/pink_loader.gif" style="width:200px"/></div>');
	
	$.post( api_url + "/report.php", { 
				func:"create_date_list", 				
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
			html +=		'			<th>Bill ID</th>';
			html +=		'			<th>Doctor</th>';
			html +=		'			<th>User</th>';
			html +=		'			<th>App. Date</th>';	
			html +=		'			<th>Created On</th>';			
			html +=		'			<th>Percentage</th>';			
			html +=		'			<th style="text-align:right;">Amount</th>';            
			html +=		'		</tr>';
			html +=		'	</thead>';
			html +=		'	<tbody>';
			var i=1;
			var total = 0;
			for(var key in data.data)
			{					
				html +=		'	<tr id="element_'+ data.data[key]['id'] +'">';
				html +=		'		<td>'+ i +'</td>';	
				html +=		'		<td>'+data.data[key]['id']+'</td>';
				html +=		'		<td><b>'+data.data[key]['doc_name']+'</b></td>';
				html +=		'		<td><b>'+data.data[key]['user_name']+'</b></td>';
				html +=		'		<td>'+data.data[key]['app_date']+'</td>';
				html +=		'		<td>'+data.data[key]['create_on']+'</td>';
				html +=		'		<td>'+data.data[key]['per_visit_change']+' % '+data.data[key]['actora_percentage']+' </td>';
				html +=		'		<td style="text-align:right;"> Rs. '+data.data[key]['amount']+'/-  </td>';								
				html +=		'	</tr>';			
				
				i++;

				total = total + parseInt(data.data[key]['amount']);			
			}		
			
			html +=		'	<tr><td colspan="7" style="text-align:right;"><b>Total:</b></td> <td style="text-align:right"><b>Rs. '+total+'/- </b></td></tr>'
			html +=		'	</tbody>';
			html +=		'</table>';
			
			console.log(data);	
			$(".list").html(html);

			$("#r_title").html('<i class="icon-comments"></i> Report showing from '+search_date_from+" to "+search_date_to);
			$("#download_btn").removeClass('hide');
			$(".btn").show();
		});
}
//search_btn_app_date
//search_btn_create_date

$("#search_btn_msg").click(function()
{
	if($("#search_date_from").val() == "" && $("#search_date_to").val())
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() && $("#search_date_to").val()=="")
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() == "" && $("#search_date_to").val() == "")
	{
		alert("Please select a date range.");		
	}
	else
	{
		var date1 = new Date($("#search_date_from").val());
		var date2 = new Date($("#search_date_to").val());
		var timeDiff = date2.getTime() - date1.getTime();
		
		if(timeDiff >= 0)
		{
			$("#download_btn").addClass('hide');
			loadPageMsg();		
		}
		else
		{
			alert("From date must be lower than to date.");		
		}
		
	}	
})

$("#search_btn_app_date").click(function()
{
	if($("#search_date_from").val() == "" && $("#search_date_to").val())
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() && $("#search_date_to").val()=="")
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() == "" && $("#search_date_to").val() == "")
	{
		alert("Please select a date range.");		
	}
	else
	{
		var date1 = new Date($("#search_date_from").val());
		var date2 = new Date($("#search_date_to").val());
		var timeDiff = date2.getTime() - date1.getTime();
		
		if(timeDiff >= 0)
		{
			$("#download_btn").addClass('hide');
			loadPageAppDate();		
		}
		else
		{
			alert("From date must be lower than to date.");		
		}
		
	}	
})

$("#search_btn_create_date").click(function()
{
	if($("#search_date_from").val() == "" && $("#search_date_to").val())
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() && $("#search_date_to").val()=="")
	{
		alert("Please enter valid date range.");
	}
	else if($("#search_date_from").val() == "" && $("#search_date_to").val() == "")
	{
		alert("Please select a date range.");		
	}
	else
	{
		var date1 = new Date($("#search_date_from").val());
		var date2 = new Date($("#search_date_to").val());
		var timeDiff = date2.getTime() - date1.getTime();
		
		if(timeDiff >= 0)
		{
			$("#download_btn").addClass('hide');
			loadPageCreateDate();	
		}
		else
		{
			alert("From date must be lower than to date.");		
		}
		
	}	
})
$("#download_btn").click(function(){
	$("#download_btn").show();
	var search_date_from  = $("#search_date_from").val();
	var search_date_to  = $("#search_date_to").val();

	var x = confirm("Are you sure to generate Report!!");
	if(x)
	{
		$("#download_btn").hide();		
		$.post( api_url + "/report.php", 
		{ 
			func:"create_csv", 
			search_date_from:search_date_from,
			search_date_to:search_date_to		

		})
		.done(function(data){
			$("#download_btn").show();
			//alert(data);
			data = JSON.parse(data);
			if(data.success)
			{
				//alert("Report Generated Success.");
				window.location.href = "https://www.streetdelight.com/report_csv/"+data.file_name;
				//$("#download_link").attr("href", "https://www.streetdelight.com/report_csv/"+data.file_name);
				//$("#download_link").attr("download",data.file_name);
				//$( "#download_link" ).trigger( "click" );
			}
			else
			{
				$("#succ_msg").show().html("");
				$("#err_msg").hide().html(data.msg);	
			}	
		})
	}
})