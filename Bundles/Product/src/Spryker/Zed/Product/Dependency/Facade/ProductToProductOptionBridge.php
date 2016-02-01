<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Spryker\Zed\ProductOption\Business\ProductOptionFacade;

class ProductToProductOptionBridge implements ProductToProductOptionInterface
{

    /**
     * @var ProductOptionFacade
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Business\ProductOptionFacade $productOptionFacade
     */
    public function __construct($productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param int $idProduct
     * @param string $localeCode
     *
     * @return mixed
     */
    public function getProductOptionsByIdProduct($idProduct, $localeCode)
    {
        return $this->productOptionFacade->getProductOptionsByIdProduct($idProduct, $localeCode);
    }

}
