<?php
class Gdav_SearchPlus_IndexController  extends Mage_Core_Controller_Front_Action{
	
	private $query = '';
    private $store = '';
    private $enableimage = '';
    private $enabledescription = '';
    private $descriptionchars = '';
	
	private function _sendJson(array $data = array())
    {
        @header('Content-type: application/json');
        echo json_encode($data);
        exit();
    }
	
    private function _trim($text, $len, $delim='...')
	{
      if (@mb_strlen($text) > $len) {
        $whitespaceposition = mb_strpos($text," ",$len)-1;
	        if( $whitespaceposition > 0 ) {
	            $chars = count_chars(mb_substr($text, 0, ($whitespaceposition+1)), 1);
	            $text = mb_substr($text, 0, ($whitespaceposition+1));
			}
			
		return $text . $delim;
      } else {
	  
    	return $text;
		  
      }

    }
	  
	private function _getResults($query, $store)
	{
		$productstoshow = Mage::getStoreConfig('search_plus/general/productstoshow');
		$imageheight = Mage::getStoreConfig('search_plus/general/imageheight');
		$imagewidth = Mage::getStoreConfig('search_plus/general/imagewidth');

		$collection = Mage::getResourceModel('searchplus/collection')->getNewCollection($query);
		
		$suggestion = array();
		$description = array();
		$data = array();
		$image = array();
		
		foreach($collection as $product){
			
			$_product = Mage::getModel('catalog/product')->load($product->getId());

			$ean = $_product->getData('ean');

			//overridden
			$collection = Bm_Mk::getStoresProductCollection($product->getId(),$ean);

        	$first = $collection->getFirstItem();

        	if($first->getQty() > 0){

        		$productname=$_product->getName();
			
				$suggestion[] = $productname;
				$data[] = $_product->getProductUrl();
				
				if($this->enableimage > 0){
					$image[] =  '<img class="searchplusimage" src="'.Mage::helper('catalog/image')->init($_product, 'thumbnail')->resize($imagewidth, $imageheight)->__toString().'" alt="'.$productname.'">';
				} else {
					$image[]='';
				}
				
				if($this->enabledescription > 0){
					$description[] =  '<br /><span class="searchplusdescription">'.strip_tags($this->_trim($_product->getShortDescription(), $this->descriptionchars)).'</span>';
				} else {
					$description[]='';
				}

        	}


			
		}
        $searchURL = Mage::helper('catalogsearch/data')->getResultUrl($this->query);

        $headertext = Mage::getStoreConfig('search_plus/general/headertext') . ' ' .
        " <a href='{$searchURL}'>{$this->query}</a>";

        $footertext = Mage::getStoreConfig('search_plus/general/footertext') . ' ' .
        " <a href='{$searchURL}'>{$this->query}</a>";

        $result= array('query' => $this->query,
					   'suggestions' => $suggestion,
					   'data' => $data,					   
					   'image' => $image,
					   'description'    => $description,
                       'headertext'     => $headertext,
                       'footertext'     => $footertext
					   );
		
		return($result);
		
	}
    
	
	public function indexAction(){
		$this->query = (isset($_GET['query'])) ? Mage::helper('core')->htmlEscape($_GET['query']) : '';
		$this->enableimage = (isset($_GET['enableimage'])) ? $_GET['enableimage'] : 0;		
    	$this->store = (isset($_GET['store'])) ? Mage::helper('core')->htmlEscape($_GET['store']) : Mage::app()->getStore()->getStoreId();
		$this->enabledescription = (isset($_GET['enabledescription'])) ? $_GET['enabledescription'] : 0;
		$this->descriptionchars = (isset($_GET['descriptionchars'])) ? $_GET['descriptionchars'] : 100;
		$this->_sendJson($this->_getResults($this->query, $this->store));
	}
	
}
