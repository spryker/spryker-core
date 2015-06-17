<?php

namespace SprykerFeature\Client\Cart\Helper;

use SprykerFeature\Client\Catalog\Model\Catalog;
use SprykerFeature\Client\Catalog\Model\CatalogInterface;

class CatalogHelper
{

    /**
     * @var CatalogInterface
     */
    protected $catalogModel;

    /**
     * @param CatalogInterface $catalogModel
     */
    public function __construct(CatalogInterface $catalogModel)
    {
        $this->catalogModel = $catalogModel;
    }

    /**
     * @param OrderItemCollection $cartItems
     * @return array
     */
    public function getProductDataForCartItems(OrderItemCollection $cartItems)
    {
        $itemSkus = [];
        foreach ($cartItems as $item) {
            $itemSkus[] = $item->getSku();
        }
        if (empty($itemSkus)) {
            return [];
        }

        return $this->catalogModel->getProductDataBySkus($itemSkus, Catalog::INDEXKEY_SKU);
    }
}
