<?php
/**
 *  
 *  Copyright (C) 2013
 *
 *
 *  @who	   	PAJ
 *  @info   	paj@gaiterjones.com
 *  @license    blog.gaiterjones.com
 * 	
 *
 */

class PageMainHTML {


function html()
{
$_HTML[] = array
	(
    'page' => array
    	(
	    	'*',
	    ),
    'html' => '<!DOCTYPE html>
<!-- This document was successfully checked as HTML5! -->
<html>
<head>
<title>'. $this->__t->__('Magento Orders'). '</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/poshytip/src/tip-twitter/tip-twitter.css" />
<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox.css?v=2.0.6" media="screen" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script src="js/poshytip/src/jquery.poshytip.js"></script>
<script src="js/jquery.editinplace.js"></script>
<script src="lib/js/script.js"></script>
<script src="js/fancybox/jquery.fancybox.js?v=2.0.6"></script>
</head>
<body>
<script type="text/javascript">

$(document).ready(function() {

	// -- init infotip
	//
	$(".infotip").poshytip({
		className: "tip-twitter",
		showTimeout: 1,
		alignTo: "target",
		alignX: "center",
		offsetY: 5,
		allowTipHover: false,
		fade: true,
		timeOnScreen: 3000,
		liveEvents: true,
		slide: false
	});

	// -- add vat check box
	//
	var addvat='. ($this->__config->get('addvat') ? 'true' : 'false'). ';
	
	$("#form_1_addvat").change(function() {
		if(this.checked) {
			addvat=true;
		} else {
			addvat=false;
		}
	});
	
	// -- click li to check/uncheck select check box
	//
	$("#orderlist").on("click", ".ordercontainer", function() {
	  var checkbox = $(this).parent().parent().find("input:checkbox[name=form_1_order_ul_select_order]");
	  checkbox.attr("checked", !checkbox.attr("checked"));
	  return false;
	});	
	
	$("#orderlist").on("dblclick", ".ordercontainer", function() {
		var checkbox = $(this).parent().parent().find("input:checkbox[name=form_1_order_ul_select_order]");
		openFB(checkbox.val());
		return false;
	});	

	// -- select all
	//
	$("#orderlist").on("click", "#form_1_order_ul_select_allorders", function() {
		var status = $(this).is(":checked");
		$("input:checkbox[name=form_1_order_ul_select_order]").each(function() {
			this.checked = status;
		});
	});	
	
	// -- select unack
	//
	$("#orderlist").on("click", "#form_1_order_ul_select_allorders_notack", function() {
		var status = $(this).is(":checked");
		$("input:checkbox.form_1_order_ul_select_order_notack[name=form_1_order_ul_select_order]").each(function() {
			this.checked = status;
		});
	});		

	// -- view invoice buttoin
	//
	$("#form_1_viewinvoice").click(function(){
	
		var orderid = new Array();
		var orderselected=false;

			$("input:checkbox[name=form_1_order_ul_select_order]:checked").each(function() {
			   orderid.push($(this).val());
			   orderselected=true;
			});

			if (orderselected) {
				openFB(orderid.join("|"));
			} else {
				alert ("Please select an order.");
			}
	});	
		
	// -- print invoice button
	//
	$("#form_1_printinvoice").click(function(){
	
		var orderid = new Array();
		var orderselected=false;

			$("input:checkbox[name=form_1_order_ul_select_order]:checked").each(function() {
			   orderid.push($(this).val());
			   orderselected=true;
			});		
		
			if (orderselected) {
					// create print url iframe
					var URL="invoice/index.phtml?orderid=" + orderid.join("|") + (addvat ? "&addvat=" + addvat : "") + "&print";
					$("<iframe id=\"printFrame\" src=\"" + URL + "\" width=\"0\" height=\"0\" frameborder=\"0\" scrolling=\"no\"/>").insertAfter($("body"));
			} else {
				alert ("Please select an order.");
			}		
	});	
	
	// -- fancybox iframe
	//
	function openFB(orderid) {
	 $.fancybox({
	 "href" : "invoice/index.phtml?orderid=" + orderid + (addvat ? "&addvat=" + addvat : ""),
	 "type" : "iframe",
	 "width": $(window).width(),
	 "height": $(window).height(),
	 "autoSize" : false
	 });
	}	

});	
</script>

<!-- START WRAPPER -->
<div id="wrapper">
'
);

// content html
$_HTML[] = array
	(
    'page' => array
    	(
	    	'*','header',
	    ),
    'html' => '
			<!-- START CONTENT -->
            <div id="content">
				<div id="form">
						<fieldset>
							<legend>'. $this->__t->__('Magento Orders'). '</legend>
								<div id="buttons">
									<span class="clickable" id="form_1_refreshinvoice"><img class="infotip" alt="'. $this->__t->__('refresh'). '" title="'. $this->__t->__('refresh'). '" width="64" src="images/refresh-icon.png"></span>						
									<span class="clickable" id="form_1_viewinvoice"><img class="infotip" alt="'. $this->__t->__('view invoice'). '" title="'. $this->__t->__('view invoice'). '" width="64" src="images/view-icon.png"></span>
									<span class="clickable" id="form_1_printinvoice"><img class="infotip" alt="'. $this->__t->__('print invoice'). '" title="'. $this->__t->__('print invoice'). '" width="64" src="images/print-icon.png"></span>							
									<span style="display:inline-block; vertical-align: top; margin-top:10px;" id="vatrate" class="infotip" title="'. $this->__t->__('Include VAT'). '">'. $this->__t->__('VAT'). ': % '. $this->__config->get('vatrate'). '</span>
									<input type="checkbox" id="form_1_addvat" name="form_1_addvat" '. ($this->__config->get('addvat') ? 'checked' : ''). '>
									
								</div>
								<div style="padding: 10px 0px 0px 0px;" id="date_time"></div>								
								<div id="orderlist">'.
									$this->get('orderulhtml'). '
								</div>							
						</fieldset>
				</div>
			</div>
			<!-- END CONTENT -->
'
);
		

// html to create error
$_HTML[] = array
	(
    'page' => array
    	(
	    	'error',
	    ),
	'html' => '<div id="errormessage">('. $this->get('errorMessage'). ')</div>'
	);	
	

// footer
$_HTML[] = array
	(
    'page' => array
    	(
	    	'*',
	    ),
	'html' => '

</div>
<!-- END WRAPPER -->
</body>
</html>
'
);





$this->set('html',$_HTML);
	
}


}
?>