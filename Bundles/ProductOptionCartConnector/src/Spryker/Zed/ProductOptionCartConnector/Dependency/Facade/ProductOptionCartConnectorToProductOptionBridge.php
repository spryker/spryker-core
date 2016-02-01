<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCartConnector\Dependency\Facade;

use Spryker\Zed\ProductOption\Business\ProductOptionFacade;
use Generated\Shared\Transfer\ProductOptionTransfer;

class ProductOptionCartConnectorToProductOptionBridge implements ProductOptionCartConnectorToProductOptionInterface
{

    /**
     * @var ProductOptionFacade
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
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $idLocale)
    {
        return $this->productOptionFacade->getProductOption($idProductOptionValueUsage, $idLocale);
    }

}
