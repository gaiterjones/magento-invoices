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
 * Main Page DATA class
 * -- Organises data for a page
 * @access public
 * @return nix
 */
class PageMainData
{
	function getOrdersHTML()
	{
		$_ULHtml='';
		$_count=0;
		
		$_obj=new MagentoCollection();
			$_storeID=$this->get('storeid');
		
		$_obj->getOrdersByDate($this->get('ordertimerange'));
		
		$_collection=$_obj->get('collection');
		
		unset($_obj);
		
		date_default_timezone_set($this->get('timezone'));
		
		// -- iterate magento product collection
		foreach ($_collection as $order) {
		
			$_count++;
			$_orderID= $order->getIncrementId();
			$_orderStatus=$order->getStatus();
			
			$_orderAcknowledged=false;
			if (file_exists($this->get('cachefolder').md5($_orderID). 'MageInvoice')) {
				$_orderAckTime=date ("F d Y H:i:s", filemtime($this->get('cachefolder').md5($_orderID). 'MageInvoice'));
				$_orderAcknowledged=true;
			}
		
			$_ULHtml=$_ULHtml. '
			<li id="order-'. $_orderID.'">'.
						($_orderStatus != 'canceled' ? '<input type="checkbox" class="'. ($_orderAcknowledged ? 'form_1_order_ul_select_order_ack' : 'form_1_order_ul_select_order_notack'). '" name="form_1_order_ul_select_order" value="'. $_orderID. '">' : '').'
						<a href="#">
							<div class="ordercontainer"><div class="ordericon"><img class="infotip" width="64" alt="Order '. $_orderID. '" title="Order '. $_orderID. '" id="order-icon-'. $_orderID. '" src="images/order-icon.png">'. $_orderID. '</div><div class="ordertext">'. ($_orderAcknowledged ? '<img class="infotip" title="'. $this->__t->__('Printed'). ' '. $_orderAckTime. '" alt="'. $this->__t->__('Printed'). ' '. $_orderAckTime. '" width="32" src="images/printed-icon.png"><em> ' : ''). $order->getBillingAddress()->getName(). ' - '. $order->getCustomer_email(). ' - '. $_orderStatus. ' - '. $order->getCreatedAt(). ($_orderAcknowledged ? '</em>' : ''). '</div></div>
						</a>
			</li>';
		}


		$this->set('orderulhtml','
			<p>'. $_count. ' '. $this->__t->__('Order/s'). ' | <span id="ordertimerange" class="edit_time_range">'. $this->get('ordertimerange'). '</span> '. $this->__t->__('Day/s'). '</p>
			<ul id="form_1_order_ul" class="order_ul">'. ($_count==0 ? '<li>'. $this->__t->__('No Orders Found'). '</li>' : '
			<li><input type="checkbox" id="form_1_order_ul_select_allorders" name="form_1_order_ul_select_allorders" value="null"><span class="textSmall">'. $this->__t->__('Select All'). '</span>  <input type="checkbox" id="form_1_order_ul_select_allorders_notack" name="form_1_order_ul_select_allorders_notack" value="null"><span class="textSmall">'. $this->__t->__('Select Not Printed'). '</span>
			</li>
			'. $_ULHtml) . '
			</ul>');
	
	}
	
} 
?>