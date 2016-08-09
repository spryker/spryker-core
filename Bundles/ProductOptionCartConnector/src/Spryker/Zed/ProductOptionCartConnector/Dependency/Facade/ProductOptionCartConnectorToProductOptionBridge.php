<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Dependency\Facade;

class ProductOptionCartConnectorToProductOptionBridge implements ProductOptionCartConnectorToProductOptionInterface
{

    /**
     * @var \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct($productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOptionValue($idProductOptionValueUsage)
    {
        return $this->productOptionFacade->getProductOptionValueById($idProductOptionValueUsage);
    }

}
