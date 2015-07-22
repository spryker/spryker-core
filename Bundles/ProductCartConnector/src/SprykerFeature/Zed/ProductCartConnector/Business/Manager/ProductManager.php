<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\ProductCartConnector\ChangeInterface;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var ProductFacade
     */
    private $productFacade;

    /**
     * @param ProductFacade $productFacade
     */
    public function __construct(ProductFacade $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        return $change;
    }

}
