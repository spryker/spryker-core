<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface;

class ProductOptionStorageToProductOptionFacadeBridge implements ProductOptionStorageToProductOptionFacadeInterface
{

    /**
     * @var ProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @param ProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct($productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getProductOptionValueStorePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer)
    {
        return $this->productOptionFacade->getProductOptionValueStorePrices($storePricesRequestTransfer);
    }
}
