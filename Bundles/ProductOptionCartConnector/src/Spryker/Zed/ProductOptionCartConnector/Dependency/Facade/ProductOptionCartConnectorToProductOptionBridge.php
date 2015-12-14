<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductOptionTransfer;

class ProductOptionCartConnectorToProductOptionBridge implements ProductOptionCartConnectorToProductOptionInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    protected $productOptionFacade;

    /**
     * ProductOptionExporterToProductOptionBridge constructor.
     *
     * @param \Spryker\Zed\ProductOption\Business\ProductOptionFacade $productOptionFacade
     */
    public function __construct($productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param int $idProductOptionValueUsage
     * @param int $idLocale
     *
     * @return ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $idLocale)
    {
        return $this->productOptionFacade->getProductOption($idProductOptionValueUsage, $idLocale);
    }
}
