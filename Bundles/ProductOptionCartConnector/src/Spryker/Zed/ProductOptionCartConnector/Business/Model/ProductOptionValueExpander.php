<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Shared\Price\PriceMode;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface;

class ProductOptionValueExpander implements ProductOptionValueExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface $productOptionFacade
     */
    public function __construct(ProductOptionCartConnectorToProductOptionInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductOptions(CartChangeTransfer $changeTransfer)
    {
        $priceMode = $changeTransfer->getQuote()->getPriceMode();
        foreach ($changeTransfer->getItems() as $itemTransfer) {
            $this->expandProductOptionTransfers($itemTransfer, $priceMode);
        }

        return $changeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function expandProductOptionTransfers(ItemTransfer $itemTransfer, $priceMode)
    {
        $productOptions = $itemTransfer->getProductOptions();

        foreach ($productOptions as &$productOptionTransfer) {
            $productOptionTransfer->requireIdProductOptionValue();

            $productOptionTransfer = $this->productOptionFacade->getProductOptionValue(
                $productOptionTransfer->getIdProductOptionValue()
            );

            $this->setPrice($productOptionTransfer, $productOptionTransfer->getUnitGrossPrice(), $priceMode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param int $price
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ProductOptionTransfer $productOptionTransfer, $price, $priceMode)
    {
        if (PriceMode::PRICE_MODE_NET === $priceMode) {
            $productOptionTransfer->setUnitGrossPrice(0);
            $productOptionTransfer->setUnitNetPrice($price);
        } else {
            $productOptionTransfer->setUnitNetPrice(0);
            $productOptionTransfer->setSumGrossPrice($price);
        }
    }
}
