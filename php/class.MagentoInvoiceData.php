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

 /**
 * Invoice DATA class
 * -- Organises data for an invoice
 * @access public
 * @return nix
 */
class MagentoInvoiceData
{
	function pageHeader()
	{
			$_watermarkURL=$this->get('watermarkurl');
			
			
			$_html='
			<div class="option-menu">'.
				(!empty($_watermarkURL) ? '
				  <!-- Watermark Option -->
				  <strong>Options:</strong> &nbsp;  
				  <label>
					<input id="cbk_paid" type="checkbox" />
				  '. $this->__t->__('Display watermark graphic',$this->get('orderlanguage')). '
				  </label>
				 <!-- END Watermark Option -->' : ''). 
				 '<!-- Print Button -->
				   <p>
					<a href="#" class="button" onclick="window.print(); return false;">Print</a> 
				  </p>
				 <!-- END Print Button -->  
			</div>';
			
			return ($_html);
	
	}

	function invoiceHeader()
	{
			$_logoURL=$this->get('logourl');
			$_watermarkURL=$this->get('watermarkurl');
			$_headerText=$this->get('headertext');
			
			$_html=
			(!empty($_watermarkURL) ? '
			<div id="watermark_graphic" class="watermark middle center" style="display: none;">
			  <div><img src="'. $_watermarkURL. '"></div>
			</div>' : ''). 
			
			  '<h1 style="float: right;">'. $this->__t->__('Invoice',$this->get('orderlanguage')). '</h1>
			  
			<div class="clearfix">'. 
				(!empty($_logoURL) ? '
					<div style="float: left; width: 400px; font-size: 10px;" id="brand_logo"><img src="'. $_logoURL. '"><br />' : '').
					(!empty($_headerText) ? '
						<div style=" margin: 0px 0 20px 80px;">'. $_headerText .'</div>' : '').
					'</div>
			</div>';
			  
			  return ($_html);

	}
	
	
	function invoiceFooter()
	{
	  
	  $_showComments=$this->get('showcomments');
	  $_customerOrderComments=$this->get('customerordercomments');
	  $_footerText=$this->get('footertext');
	  
	  $_html=($_showComments || $_customerOrderComments ? '
	  <div id="notes"> <strong>'. $this->__t->__('Notes',$this->get('orderlanguage')). ':</strong><br />
		<textarea id="comments_area">'. ($_customerOrderComments ? $_customerOrderComments : $this->__t->__('Type your comments here.',$this->get('orderlanguage'))). '</textarea>
	  </div>' : '').
		(!empty($_footerText) ? '
	  <div id="footer">
		<p>
			<!-- Use for screen media footer text  -->
			<strong>'. $this->__t->__($_footerText,$this->get('orderlanguage')). '</strong>
		</p>
	  </div>' : '').
	  '<div id="printFooter">
		<p>
			<!-- Use for printed media footer text  -->
		</p> 
	  </div>';
		
		return ($_html);
	}	
	
	function invoiceHeaderTable()
	{
		$_addVat=$this->get('addvat');
		$_vatRate=(int)$this->get('vatrate');
		$_count=0;
		$_orderStoreID=null;
		$_customerGroupID=null;

		foreach ($this->__['order'] as $_order) {
			$_count ++;
			
			$_orderStoreID=$_order->getStoreId();
			$_customerGroupID = $_order->getCustomerGroupId();
			
			$_orderLanguage = $this->__['storeslanguage'][$_orderStoreID-1];
				if (empty($_orderLanguage)) { $_orderLanguage=$this->get('languagecode'); }
			$this->set('orderlanguage',$_orderLanguage);
			
			if ($_addVat && $_vatRate)
			{
				$_shippingInclTax = $_order->getShippingAmount() + $_order->getShippingTaxAmount();
				$_order->setShippingAmount($_shippingInclTax);
			}
			
			if ($_order->hasInvoices() && !$this->get('displayordernumber')) { // we can show the invoice number if one exists
				$_invoiceIdText=$this->__t->__('Invoice',$this->get('orderlanguage'));
				foreach ($_order->getInvoiceCollection() as $_eachInvoice) {
					$_invoiceId = $_eachInvoice->getIncrementId();
				}
			} else { // get order number instead
					$_invoiceIdText=$this->__t->__('Order',$this->get('orderlanguage'));
					$_invoiceId = $_order->getIncrementId();
			}
			
			$_customerEmail=$_order->getCustomerEmail(); 
			$_billingAddress=$_order->getBillingAddress()->format('html');
			$_customerEmail=$_order->getCustomerEmail();
			$_shippingAddress=$_order->getShippingAddress()->format('html');
			$_paymentMethod=$_order->getPayment()->getMethodInstance()->getTitle();
			$_shippingDescription=$_order->getShippingDescription();
			
			$_customerOrderComments=$_order->getvweCustomerordercomment();
		}
		
		if ($_count < 1) { throw new exception ('No Order Data Found!'); }
	  
		  $_html='
		  <table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
			  <td valign="top"><p><strong>'. $this->__t->__('Invoice Address',$this->get('orderlanguage')). ':</strong></p>
				<span class="captitalize">'. $_billingAddress. '</span>
				<p><a href="mailto:' . $_customerEmail . '"><u>' . $_customerEmail . '</u></a></p></td>
			  <td valign="top"><p><strong>'. $this->__t->__('Delivery Address',$this->get('orderlanguage')). ':</strong></p>
				<span class=capitalize">'. $_shippingAddress. '</span>
			  </td>
			  <td valign="top"><p>'. $_invoiceIdText . '  '. $this->__t->__('Number',$this->get('orderlanguage')). ': <strong>'. $_invoiceId. '</strong></p>
				<p>
				'. $this->__t->__('Store ID',$this->get('orderlanguage')). ':
				  <strong>'.$_orderStoreID. ' ('. $this->get('orderlanguage'). ')</strong><br />				
				'. $this->__t->__('Date',$this->get('orderlanguage')). ':
				  <strong>'. date("d.m.Y"). '</strong><br />
				'. $this->__t->__('Payment Method',$this->get('orderlanguage')). ':
				  <strong>'. $_paymentMethod. '</strong><br />
				'. $this->__t->__('Delivery Per',$this->get('orderlanguage')). ':
				  <strong>'. $_shippingDescription. '</strong><br />
				</p>	
			</tr>
		  </table>';
		  
		  // save comments for later
		  $this->set('customerordercomments',$_customerOrderComments);
		  
		  // -- return html
		  return ($_html);
	
	}
	
	function invoiceItemsTable()
	{	
	  
		$_addVat=$this->get('addvat');
		$_vatRate=(int)$this->get('vatrate');
		
		foreach ($this->__['order'] as $_order) {
			$_items = $_order->getAllItems();
			$_orderSubTotal=$this->formatMoney ($_order->getSubtotal());
			$_shippingDescription=$_order->getShippingDescription();
			$_shippingAmount=$this->formatMoney ($_order->getShippingAmount());
			$_orderDiscountAmount=$this->formatMoney ($_order->getDiscountAmount());
			$_orderDiscountDescription=$_order->getDiscountDescription();
			$_orderTaxAmount=$this->formatMoney ($_order->getTaxAmount());
			$_orderTotal=$this->formatMoney ($_order->getGrandTotal());
		}
		
		$x = 0;
		$_row=null;
		
		foreach($_items as $_item) {
			
			if ($_item->getParentItem()) continue;
			
			//$_product = Mage::getModel('catalog/product')->load($_item->getProductId());
			
			// get image from either small image or normal image
			//$_imageURL = Mage::helper('catalog/image')->init($_product, 'small_image')->resize(50);
			//if (empty($_imageURL)) {$_imageURL = Mage::helper('catalog/image')->init($_product, 'image')->resize(50);}
			
			$_rows=$_rows. '<tr class="dataTableRow ' . ($x%2==0 ? "" : "alt") . '">
							<td class="dataTableContent" valign="top" align="">' . number_format($_item->getQtyOrdered(), 0) . '</td>
							<td class="dataTableContent" valign="top">' . $_item->getSku() . '</td>
							<td class="dataTableContent" valign="top">'. ($_showImages && !empty($_imageURL) ? '<img src="'. $_imageURL. '">  ' : '') . $_item->getName();

			if ($_options = $_item->getProductOptions()) {
			
				$result = array();
			
				if (isset($_options['options'])) {
					$result = array_merge($result, $_options['options']);
				}
				if (isset($_options['additional_options'])) {
					$result = array_merge($result, $_options['additional_options']);
				}
				if (!empty($_options['attributes_info'])) {
					$result = array_merge($_options['attributes_info'], $result);
				}
				
				// loop and print
				foreach ($result as $_option) {
						$_rows=$_rows. "<br /><em>" . $_option["label"] . ":</em>&nbsp;&nbsp;" . $_option["value"];
				}
				
				$finalPrice=$_item->getPrice();
				$rowTotal=$_item->getRowTotal();
				
				// add tax to product price
				if ($_addVat && $_vatRate)
				{
					$finalPrice=$this->formatMoney ($_item->getPrice() + ($_item->getPrice() * ($_vatRate/100)));
					$rowTotal=$this->formatMoney ($_item->getRowTotal() + ($_item->getRowTotal() * ($_vatRate/100)));
					//$finalPrice=Mage::helper('tax')->getPrice($_product, $_product->getFinalPrice());
				}
				
				
				
				
				
			}
			

		  $_rows=$_rows. '</td>
						<td class="dataTableContent" align="right" valign="top"><b>' . $this->formatMoney ($finalPrice) .  '</b></td>
						<td class="dataTableContent" align="right" valign="top"><b>' . $this->formatMoney ($rowTotal) . '</b></td>
						</tr>';
		  
		  $x++;
		  
		}
		
		$_html='<table border="0" width="99%" cellspacing="0" cellpadding="0">
		<tr class="dataTableHeadingRow">
		  <td class="dataTableHeadingContent">'. $this->__t->__('Quantity',$this->get('orderlanguage')). '</td>
		  <td class="dataTableHeadingContent">'. $this->__t->__('Item',$this->get('orderlanguage')). '</td>
		  <td class="dataTableHeadingContent" align="left">'. $this->__t->__('Description',$this->get('orderlanguage')). '</td>
		  <td class="dataTableHeadingContent" align="right">'. $this->__t->__('Price',$this->get('orderlanguage')). '</td>
		  <td class="dataTableHeadingContent" align="right">'. $this->__t->__('Total',$this->get('orderlanguage')). '</td>
		</tr>
		'. $_rows . '
		<tr>
		  <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2" class="totals">
			  <tr>
				<td align="right" class="label">'. $this->__t->__('Subtotal',$this->get('orderlanguage')). '</td>
				<td align="right"><strong>'. ($_addVat && $_vatRate ? $this->formatMoney ($_orderSubTotal + $_orderSubTotal * ($_vatRate/100)) : $_orderSubTotal). '</strong></td>
			  </tr>'.
			  ($_order->getShippingDescription() ? '
			  <tr>
				<td align="right" class="label">'. $_shippingDescription. '</td>
				<td align="right"><strong>'. $_shippingAmount. '</strong></td>
			  </tr>' : ''). 
			  ($_orderDiscountAmount != 0 ? '
			  <tr>
				<td align="right" class="label">'. $this->__t->__('Discount',$this->get('orderlanguage')). ''. $_orderDiscountDescription .'</td>
				<td align="right"><strong>'. $_orderDiscountAmount. '</strong></td>
			  </tr>' : '').
			  
			  ($_orderTaxAmount > 0 ? '
			  <tr>
				<td align="right" class="label">'. $this->__t->__('VAT',$this->get('orderlanguage')). ' '. ($_addVat ? ' ('. $_vatRate. '%)' : ''). '</td>
				<td align="right"><strong>'. $_orderTaxAmount. '</strong></td>
			  </tr>' : '').
			  '<tr class="total">
				<td align="right" class="label"><strong>'. $this->__t->__('Total',$this->get('orderlanguage')). '</strong></td>
				<td align="right"><strong>'. $_orderTotal. '</strong></td>
			  </tr>
			</table></td>
		</tr>
	  </table>';		



	return ($_html);
  
  }
	
} 
?>