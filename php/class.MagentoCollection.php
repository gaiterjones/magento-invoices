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
 * Magento collection class
 * a generic Magento class for collecting data
 */
class MagentoCollection extends Magento {



	public function __construct() {

		parent::__construct();
		
		$this->getCategories();


	}
	
	public function getCategories() {
	
		// get category collection
		$collection= Mage::getModel('catalog/category')
			->getCollection() 
			->addAttributeToSelect('name') 
			->addAttributeToSelect('is_active');
		
		$_categoryProductCount=array();	
		
		// determine categories that contain products
		foreach ($collection as $_category)
		{
			if($_category->getName() != '')
			{
				$_productCollection = Mage::getModel('catalog/category')->load($_category->getId())
				 ->getProductCollection()
				 ->addAttributeToFilter('status', 1)
				 ->addAttributeToFilter('visibility', 4);
				 
				$_categoryProductCount[$_category->getId()]= $_productCollection->count();
			}
		}
		
		$this->set('categories',$collection); 
		$this->set('categoriesproductcount',$_categoryProductCount); 
	}
	
	
	public function getNewProducts($storeId='1',$_page=1,$_count=18)
	{  
 
			// load collection
			$todayDate  = Mage::app()->getLocale()->date()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

			$collection = Mage::getModel('catalog/product')
                    ->getCollection()   
                    ->setStoreId($storeId)
	                 ->addStoreFilter($storeId)  
					 ->addAttributeToFilter('status', 1)
					 ->addAttributeToFilter('visibility', 4)
					 ->addAttributeToSelect('sku')
					 ->addAttributeToSelect('name')
					 ->addAttributeToSelect('description')
					 ->addAttributeToSelect('short_description')
					 ->addAttributeToSelect('url')
					 ->addAttributeToSelect('image')
					 ->addAttributeToSelect('price')             
                     ->addAttributeToFilter('news_from_date', array('date' => true, 'to' => $todayDate))
                     ->addAttributeToFilter('news_to_date', array('or'=> array(
                        0 => array('date' => true, 'from' => $todayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                     ), 'left')
                     ->addAttributeToSort('news_from_date', 'desc')
                     ->addAttributeToSort('created_at', 'desc')
                     ->setPage($_page,$_count);   

        
            $this->set('collection',$collection); 
	}
	
	public function getCategoryProducts($storeId='1',$category=1,$_page=1,$_count=18)
	{

		$products = Mage::getModel('catalog/category')->load($category)
		     ->getProductCollection()
             ->setStoreId($storeId)
	         ->addStoreFilter($storeId) 
			 ->addAttributeToFilter('status', 1)
			 ->addAttributeToFilter('visibility', 4)
			 ->addAttributeToSelect('sku')
			 ->addAttributeToSelect('name')
			 ->addAttributeToSelect('description')
			 ->addAttributeToSelect('short_description')
			 ->addAttributeToSelect('url')
			 ->addAttributeToSelect('image')
			 ->addAttributeToSelect('price')
		    //->setOrder('price', 'ASC');
		    ->addAttributeToSort('entity_id', 'DESC')
            ->setPage($_page,$_count);
     
     $this->set('collection',$products); 
	}


	public function getBestsellingProducts($storeId='1')
	
	//Get Bestselling products for last 30 days
	
	{

    // number of products to display
    $productCount = 18;
 
    // get today and last 30 days time
    $today = time();
    $last = $today - (60*60*24*30);
 
    $from = date("Y-m-d", $last);
    $to = date("Y-m-d", $today);
     
    // get most viewed products for current category
    $products = Mage::getResourceModel('reports/product_collection')
                    ->addAttributeToSelect('*')     
                    ->addOrderedQty($from, $to)
                    ->setStoreId($storeId)
                    ->addStoreFilter($storeId)                  
                    ->setOrder('ordered_qty', 'desc')
                    ->setPageSize($productCount);
     
    Mage::getSingleton('catalog/product_status')
            ->addVisibleFilterToCollection($products);
    Mage::getSingleton('catalog/product_visibility')
            ->addVisibleInCatalogFilterToCollection($products);
     
     $this->set('collection',$products); 
	}

	public function getAllProducts($_storeID=0,$_page=1,$_count=18)
	{  
 
		
		$collection = Mage::getModel('catalog/product')
                         ->getCollection()
                         ->setStoreId($_storeID)
	                     ->addStoreFilter($_storeID)  
						 ->addAttributeToFilter('status', 1)
						 ->addAttributeToFilter('visibility', 4)
						 ->addAttributeToSelect('sku')
						 ->addAttributeToSelect('name')
						 ->addAttributeToSelect('description')
						 ->addAttributeToSelect('short_description')
						 ->addAttributeToSelect('url')
						 ->addAttributeToSelect('image')
						 ->addAttributeToSelect('price')
                         ->addAttributeToSort('entity_id', 'DESC')
                         ->setPage($_page,$_count);

		
		$this->set('collection',$collection); 

	}
	
	public function getExternalProducts($_storeID=0)
	{  
 
		$collection = Mage::getModel('catalog/product')
                         ->getCollection()
                         ->setStoreId($_storeID)
	                     ->addStoreFilter($_storeID)  
						 ->addAttributeToFilter('status', 1)
						 ->addAttributeToFilter('ext_enable', 1)
						 ->addAttributeToSelect('sku')
						 ->addAttributeToSelect('price')
						 ->addAttributeToSelect('ext_title')
						 ->addAttributeToSelect('name')
						 ->addAttributeToSelect('ext_description')
						 ->addAttributeToSelect('ext_ebayprice')
						 ->addAttributeToSelect('ext_amazonprice')
						 ->addAttributeToSelect('ext_ean')
                         ->addAttributeToSort('sku', 'ASC');
	
		$this->set('collection',$collection); 

	}
	
	public function getProductBySku($_storeID=0,$_sku)
	{  
 
		$collection = Mage::getModel('catalog/product')
                         ->getCollection()
                         ->setStoreId($_storeID)
	                     ->addStoreFilter($_storeID)  
						 ->addAttributeToFilter('status', 1)
						 ->addAttributeToFilter('ext_enable', 1)
						 ->addAttributeToFilter('sku', array('like'=> '%'. $_sku. '%'))
						 ->addAttributeToSelect('sku')
						 ->addAttributeToSelect('price')
						 ->addAttributeToSelect('ext_title')
						 ->addAttributeToSelect('name')
						 ->addAttributeToSelect('ext_description')
						 ->addAttributeToSelect('ext_ebayprice')
						 ->addAttributeToSelect('ext_amazonprice')
						 ->addAttributeToSelect('ext_ean')
                         ->addAttributeToSort('sku', 'ASC');
	
		$this->set('collection',$collection); 

	}	

	public function getChildProducts($_parentId,$_storeID=0,$_type='grouped')
	{  
 
	$product = Mage::getModel('catalog/product')->load($_parentId);
	
	if ($_type==='configurable') {

		$childIds = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($product->getId());
	} else {
		$childIds = Mage::getModel('catalog/product_type_grouped')->getChildrenIds($product->getId());
	}

    $i = 1;

    foreach ($childIds as $key => $val){

        foreach($val as $keyy => $vall){
            $arr[$i] = $vall;
            $i++;
        }

    }
	
		$collection = Mage::getModel('catalog/product')
			->getCollection()
            ->setStoreId($_storeID)
	        ->addStoreFilter($_storeID)			
			->addAttributeToSelect('name')
			->addAttributeToSelect('price')
			->addAttributeToSelect('ext_description')
			->addAttributeToSelect('ext_ebayprice')
			->addAttributeToSelect('ext_amazonprice')
			->addAttributeToSelect('ext_ean')			
			->addFieldToFilter('entity_id',array('in' =>array($arr)));

	
	$this->set('collection',$collection); 

	}	


	public function getOverallBestsellingProducts()
	{  
	// Get overall Bestselling products
	    // number of products to display
	    $productCount = 5;
	     
	    // store ID
	    $storeId    = Mage::app()->getStore()->getId();      
	     
	    // get most viewed products for current category
	    $products = Mage::getResourceModel('reports/product_collection')
	                    ->addAttributeToSelect('*')     
	                    ->addOrderedQty()
	                    ->setStoreId($storeId)
	                    ->addStoreFilter($storeId)                  
	                    ->setOrder('ordered_qty', 'desc')
	                    ->setPageSize($productCount);
	     
	    Mage::getSingleton('catalog/product_status')
	            ->addVisibleFilterToCollection($products);
	    Mage::getSingleton('catalog/product_visibility')
	            ->addVisibleInCatalogFilterToCollection($products);
	     
	    $this->set('collection',$collection); 
	}

	 public function getOrdersByDate($_days=31)
	{
	
		$_timeRange = date('Y-m-d', strtotime("-". $_days. " day"));
		
		$_orders = Mage::getModel('sales/order')->getCollection()
			->addAttributeToFilter('created_at', array('from'  => $_timeRange))
			//->addAttributeToFilter('status', array('neq' => Mage_Sales_Model_Order::STATE_COMPLETE))
			->addAttributeToSelect('*') 
			->addAttributeToSort('created_at', 'DESC');
			;
							
		
		$this->set('collection',$_orders); 
	
	}
	
	 public function getOrdersByOrderID($_orderID,$_storeID=0)
	{
	
		$_orders = Mage::getModel('sales/order')->getCollection()
			->addAttributeToFilter('increment_id', $_orderID)
			//->addAttributeToFilter('status', array('neq' => Mage_Sales_Model_Order::STATE_COMPLETE))
			->addAttributeToSelect('*')   
			->addAttributeToSort('created_at', 'DESC');
			;
							
		
		$this->set('collection',$_orders); 
	
	}
}
?>