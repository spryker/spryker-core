<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Dependency\Facade;

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
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $idLocale)
    {
        return $this->productOptionFacade->getProductOption($idProductOptionValueUsage, $idLocale);
    }

}
