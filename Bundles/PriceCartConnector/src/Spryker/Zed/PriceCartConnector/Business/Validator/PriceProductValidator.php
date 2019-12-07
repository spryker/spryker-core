<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;

class PriceProductValidator implements PriceProductValidatorInterface
{
    public const CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY = 'cart.pre.check.price.failed';

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    protected $priceProductFilter;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface $priceProductFilter
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceProductFilterInterface $priceProductFilter
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductFilter = $priceProductFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validatePrices(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $priceProductFilters = $this->createPriceProductFilters($cartChangeTransfer);
        $validPriceProductTransfers = $this->priceProductFacade->getValidPrices($priceProductFilters);

        return $this->checkProductWithoutPricesRestriction($validPriceProductTransfers, $cartChangeTransfer, $cartPreCheckResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $validPriceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkProductWithoutPricesRestriction(
        array $validPriceProductTransfers,
        CartChangeTransfer $cartChangeTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $productWithoutPriceSkus = $this->getProductWithoutPriceSkus($validPriceProductTransfers, $cartChangeTransfer->getItems()->getArrayCopy());

        if ($productWithoutPriceSkus) {
            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($this->getFirstNotValidSku($productWithoutPriceSkus)));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string[] $productWithoutPriceSkus
     *
     * @return string
     */
    protected function getFirstNotValidSku(array $productWithoutPriceSkus): string
    {
        return array_shift($productWithoutPriceSkus);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
         ->setValue(static::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY)
         ->setParameters(['%sku%' => $sku]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return string[]
     */
    protected function getProductWithoutPriceSkus(array $priceProductTransfers, array $items): array
    {
        $totalItemSkus = array_map(function (ItemTransfer $item) {
            return $item->getSku();
        }, $items);

        $validProductSkus = array_map(function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getSkuProduct();
        }, $priceProductTransfers);

        return array_diff($totalItemSkus, $validProductSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer[]
     */
    protected function createPriceProductFilters(CartChangeTransfer $cartChangeTransfer): array
    {
        $priceProductFilters = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductFilters[] = $this->priceProductFilter->createPriceProductFilterTransfer($cartChangeTransfer, $itemTransfer);
        }

        return $priceProductFilters;
    }
}
