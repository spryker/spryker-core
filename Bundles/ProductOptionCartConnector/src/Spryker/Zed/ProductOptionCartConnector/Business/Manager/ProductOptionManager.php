<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface;

class ProductOptionManager implements ProductOptionManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface
     */
    private $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface
     */
    public function __construct(ProductOptionCartConnectorToProductOptionInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductOptions(CartChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $this->expandProductOptionTransfers($cartItem);
        }

        return $change;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     *
     * @return void
     */
    public function expandProductOptionTransfers(ItemTransfer $cartItem)
    {
        foreach ($cartItem->getProductOptions() as &$productOptionTransfer) {
            if ($productOptionTransfer->getIdOptionValueUsage() === null || $productOptionTransfer->getLocaleCode() === null) {
                throw new \RuntimeException('Unable to expand product option. Missing required values: idOptionValueUsage, localeCode');
            }

            $productOptionTransfer = $this->productOptionFacade->getProductOption(
                $productOptionTransfer->getIdOptionValueUsage(),
                $productOptionTransfer->getLocaleCode()
            );
            $productOptionTransfer->setQuantity($cartItem->getQuantity());
        }
    }

}
