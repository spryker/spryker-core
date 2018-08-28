<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface;

class ProductOptionValueExpander implements ProductOptionValueExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifierBuffer;

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface $priceFacade
     */
    public function __construct(ProductOptionCartConnectorToProductOptionFacadeInterface $productOptionFacade, ProductOptionCartConnectorToPriceFacadeInterface $priceFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
        $this->priceFacade = $priceFacade;
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

            $this->sanitizePrices($productOptionTransfer, $priceMode);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function sanitizePrices(ProductOptionTransfer $productOptionTransfer, $priceMode)
    {
        if ($priceMode === $this->getGrossPriceModeIdentifier()) {
            $productOptionTransfer->setUnitNetPrice(0);
            $productOptionTransfer->setSumNetPrice(0);

            return;
        }

        $productOptionTransfer->setUnitGrossPrice(0);
        $productOptionTransfer->setSumGrossPrice(0);
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier()
    {
        if (!isset(static::$grossPriceModeIdentifierBuffer)) {
            static::$grossPriceModeIdentifierBuffer = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifierBuffer;
    }
}
