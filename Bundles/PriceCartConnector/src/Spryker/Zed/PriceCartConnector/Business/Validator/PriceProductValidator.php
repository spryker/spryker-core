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
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class PriceProductValidator implements PriceProductValidatorInterface
{
    public const CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY = 'cart.pre.check.price.failed';
    public const CART_PRE_CHECK_MIN_PRICE_RESTRICTION_FAILED_KEY = 'cart.pre.check.min_price.failed';

    protected const MESSAGE_GLOSSARY_KEY_PRICE = '%price%';
    protected const MESSAGE_GLOSSARY_KEY_CURRENCY_ISO_CODE = '%currencyIsoCode%';

    protected const CENT_REPRESENTATION_DIVIDER = 100;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    protected $priceProductFilter;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface $priceProductFilter
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $config
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceProductFilterInterface $priceProductFilter,
        PriceCartConnectorConfig $config
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductFilter = $priceProductFilter;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validatePrices(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductFilterTransfer = $this->priceProductFilter
                ->createPriceProductFilterTransfer($cartChangeTransfer->getQuote(), $itemTransfer);

            if ($this->priceProductFacade->hasValidPriceFor($priceProductFilterTransfer)) {
                $cartPreCheckResponseTransfer = $this->checkMinPriceRestriction(
                    $cartPreCheckResponseTransfer,
                    $itemTransfer,
                    $priceProductFilterTransfer
                );

                if ($cartPreCheckResponseTransfer->getIsSuccess()) {
                    continue;
                }

                return $cartPreCheckResponseTransfer;
            }

            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($itemTransfer));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkMinPriceRestriction(
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        ItemTransfer $itemTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): CartPreCheckResponseTransfer {
        $price = $this->priceProductFacade->findPriceBySku($itemTransfer->getSku(), $priceProductFilterTransfer->getPriceTypeName());
        $sumPrice = $itemTransfer->getQuantity() * $price;

        if ($sumPrice < $this->config->getMinPriceRestriction()) {
            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessageMinPriceRestriction($priceProductFilterTransfer->getCurrencyIsoCode()));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageMinPriceRestriction(string $currencyIsoCode): MessageTransfer
    {
        $parameterMinPrice = $this->config->getMinPriceRestriction() / static::CENT_REPRESENTATION_DIVIDER;

        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_MIN_PRICE_RESTRICTION_FAILED_KEY)
            ->setParameters([
                static::MESSAGE_GLOSSARY_KEY_PRICE => $parameterMinPrice,
                static::MESSAGE_GLOSSARY_KEY_CURRENCY_ISO_CODE => $currencyIsoCode,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(ItemTransfer $itemTransfer)
    {
        return (new MessageTransfer())
         ->setValue(static::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY)
         ->setParameters(['%sku%' => $itemTransfer->getSku()]);
    }
}
