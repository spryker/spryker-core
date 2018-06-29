<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    /**
     * @var \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[]
     */
    protected $priceProductFilterPlugins = [];

    /**
     * @param \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[] $priceProductDecisionPlugins
     */
    public function __construct(array $priceProductDecisionPlugins)
    {
        $this->priceProductFilterPlugins = $priceProductDecisionPlugins;
    }

    /**
     * //todo: check??
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceValueByPriceProductCriteria(array $priceProductTransfers, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer {
        $priceProductCriteriaTransfer
            ->requirePriceMode()
            ->requirePriceType()
            ->requireIdCurrency();

        if (!$priceProductTransfers) {
            return null;
        }

        $matchedPriceProductTransfer = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->checkPriceProductByCriteria($priceProductTransfer, $priceProductCriteriaTransfer)) {
                $matchedPriceProductTransfer[] = $priceProductTransfer;
            }
        }

        //apply min strategy on dimension level
        $priceProductFilter = (new PriceProductFilterTransfer())->setPriceMode($priceProductCriteriaTransfer->getPriceMode());
        foreach ($this->priceProductFilterPlugins as $priceProductFilterPlugin) {
            $priceProductTransfers = $priceProductFilterPlugin->filter($priceProductTransfers, $priceProductFilter);
        }

        //apply min strategy overall
        return $this->minStrategy($priceProductTransfers, $priceProductFilter);
    }

    /**
     * @param PriceProductTransfer[] $priceProductTransfers
     * @param PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return PriceProductTransfer|null
     */
    protected function minStrategy(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($minPriceProductTransfer === null) {
                $minPriceProductTransfer = $priceProductTransfer;
            }

            if ($priceProductFilterTransfer->getPriceMode() === PriceProductConfig::PRICE_GROSS_MODE) {
                if ($minPriceProductTransfer->getMoneyValue()->getGrossAmount() > $priceProductTransfer->getMoneyValue()->getGrossAmount()) {
                    $minPriceProductTransfer = $priceProductTransfer;
                }
            } else {
                if ($minPriceProductTransfer->getMoneyValue()->getNetAmount() > $priceProductTransfer->getMoneyValue()->getNetAmount()) {
                    $minPriceProductTransfer = $priceProductTransfer;
                }
            }
        }

        return $minPriceProductTransfer;
    }

    protected function checkPriceProductByCriteria(PriceProductTransfer $priceProductTransfer, PriceProductCriteriaTransfer $priceProductCriteriaTransfer)
    {
        $priceProductTransfer
            ->requirePriceDimension()
            ->requirePriceTypeName()
            ->requireMoneyValue();

        if ($priceProductCriteriaTransfer->getPriceDimension() !== null) {
            if ($priceProductTransfer->getPriceDimension()->getType() !== $priceProductTransfer->getPriceDimension()->getType()) {
                return false;
            }
        }

        if ($priceProductCriteriaTransfer->getIdCurrency() !== null) {
            $priceProductTransfer->getMoneyValue()->requireCurrency();

            if ($priceProductCriteriaTransfer->getIdCurrency() !== $priceProductTransfer->getMoneyValue()->getCurrency()->getIdCurrency()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        if (count($priceProductTransfers) === 0) {
            return null;
        }

        $priceProductFilterTransfer
            ->requirePriceTypeName()
            ->requireCurrencyIsoCode()
            ->requirePriceMode();

        $priceProductTransfers = $this->findPricesByPriceProductFilter($priceProductTransfers, $priceProductFilterTransfer);

        //apply min strategy on dimension level
        foreach ($this->priceProductFilterPlugins as $priceProductFilterPlugin) {
            $priceProductTransfers = $priceProductFilterPlugin->filter($priceProductTransfers, $priceProductFilterTransfer);
        }

        //apply min strategy overall
        return $this->minStrategy($priceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function matchPricesByFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        if (count($priceProductTransfers) === 0) {
            return [];
        }

        $priceProductFilterTransfer
            ->requireCurrencyIsoCode()
            ->requirePriceMode();

        $priceProductTransfers = $this->findPricesByPriceProductFilter($priceProductTransfers, $priceProductFilterTransfer);

        //apply min strategy on dimension level
        foreach ($this->priceProductFilterPlugins as $priceProductFilterPlugin) {
            $priceProductTransfers = $priceProductFilterPlugin->filter($priceProductTransfers, $priceProductFilterTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findPricesByPriceProductFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        $matchedPriceProductTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->checkPriceProductOnFilter($priceProductTransfer, $priceProductFilterTransfer)) {
                $matchedPriceProductTransfers[] = $priceProductTransfer;
            }
        }

        return $matchedPriceProductTransfers;
    }

    /**
     * @param PriceProductTransfer $priceProductTransfer
     * @param PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function checkPriceProductOnFilter(PriceProductTransfer $priceProductTransfer, PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        if ($priceProductFilterTransfer->getPriceDimension() !== null) {
            $priceProductTransfer->requirePriceDimension();

            if ($priceProductFilterTransfer->getPriceDimension()->getType() !== $priceProductTransfer->getPriceDimension()->getType()) {
                false;
            }
        }

        if ($priceProductFilterTransfer->getCurrencyIsoCode() !== null) {
            $priceProductTransfer
                ->getMoneyValue()
                ->requireCurrency();
            $priceProductTransfer
                ->getMoneyValue()
                ->getCurrency()
                ->requireCode();

            if ($priceProductTransfer->getMoneyValue()->getCurrency()->getCode() !== $priceProductFilterTransfer->getCurrencyIsoCode()) {
                return false;
            }
        }

        if ($priceProductFilterTransfer->getPriceTypeName() !== null) {
            $priceProductTransfer->requirePriceTypeName();

            if ($priceProductFilterTransfer->getPriceTypeName() !== $priceProductTransfer->getPriceTypeName()) {
                return false;
            }
        }

        return true;
    }
}
