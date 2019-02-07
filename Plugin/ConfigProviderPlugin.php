<?php

namespace K2\BrandinCheckout\Plugin;

class ConfigProviderPlugin extends \Magento\Framework\Model\AbstractModel
{

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {

        $items = $result['totalsData']['items'];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        for($i=0;$i<count($items);$i++){

            $quoteId = $items[$i]['item_id'];
            $quote = $objectManager->create('\Magento\Quote\Model\Quote\Item')->load($quoteId);
            $productId = $quote->getProductId();
            $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
            $productBrand = $product->getResource()->getAttribute('brand')->getFrontend()->getValue($product);         
            if($productBrand == 'No' || $productBrand == 'NA'){
                $productBrand = '';
            }
            $items[$i]['brand'] = $productBrand;
        }
        $result['totalsData']['items'] = $items;
        return $result;
    }

}