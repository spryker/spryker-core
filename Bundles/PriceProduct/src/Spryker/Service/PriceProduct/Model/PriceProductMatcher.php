<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProduct\FilterStrategy\SinglePriceProductFilterStrategyInterface;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    /**
     * @var \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[]
     */
    protected $priceProductFilterPlugins = [];

    /**
     * @var \Spryker\Service\PriceProduct\FilterStrategy\SinglePriceProductFilterStrategyInterface
     */
    protected $singlePriceProductFilterStrategy;

    /**
     * @param \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface[] $priceProductDecisionPlugins
     * @param \Spryker\Service\PriceProduct\FilterStrategy\SinglePriceProductFilterStrategyInterface $singlePriceProductFilterStrategy
     */
    public function __construct(
        array $priceProductDecisionPlugins,
        SinglePriceProductFilterStrategyInterface $singlePriceProductFilterStrategy
    ) {
        $this->priceProductFilterPlugins = $priceProductDecisionPlugins;
        $this->singlePriceProductFilterStrategy = $singlePriceProductFilterStrategy;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceValueByPriceProductCriteria(array $priceProductTransfers, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer
    {
        $priceProductCriteriaTransfer
            ->requirePriceMode()
            ->requirePriceType()
            ->requireIdCurrency();

        if (!$priceProductTransfers) {
            return null;
        }

        $priceProductTransfers = $this->findPricesByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setPriceMode($priceProductCriteriaTransfer->getPriceMode());

        $priceProductTransfers = $this->applyPriceProductFilerPlugins($priceProductTransfers, $priceProductFilterTransfer);

        return $this->singlePriceProductFilterStrategy->findOne($priceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function applyPriceProductFilerPlugins(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer)
    {
        foreach ($this->priceProductFilterPlugins as $priceProductFilterPlugin) {
            $priceProductTransfers = $priceProductFilterPlugin->filter($priceProductTransfers, $priceProductFilterTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param array $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array
     */
    protected function findPricesByPriceProductCriteria(array $priceProductTransfers, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        $matchedPriceProductTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->checkPriceProductByCriteria($priceProductTransfer, $priceProductCriteriaTransfer)) {
                $matchedPriceProductTransfers[] = $priceProductTransfer;
            }
        }

        return $matchedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    protected function checkPriceProductByCriteria(PriceProductTransfer $priceProductTransfer, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): bool
    {
        $priceProductTransfer
            ->requirePriceDimension()
            ->requirePriceTypeName()
            ->requireMoneyValue();

        if (!$priceProductTransfer->getPriceDimension()->getType()) {
            return false;
        }

        if ($priceProductCriteriaTransfer->getPriceDimension() !== null) {
            if ($priceProductCriteriaTransfer->getPriceDimension()->getType() !== $priceProductTransfer->getPriceDimension()->getType()) {
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
        $priceProductTransfers = $this->applyPriceProductFilerPlugins($priceProductTransfers, $priceProductFilterTransfer);

        return $this->singlePriceProductFilterStrategy->findOne($priceProductTransfers, $priceProductFilterTransfer);
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

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findPricesByPriceProductFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function checkPriceProductOnFilter(PriceProductTransfer $priceProductTransfer, PriceProductFilterTransfer $priceProductFilterTransfer): bool
    {
        if ($priceProductFilterTransfer->getPriceDimension() !== null) {
            $priceProductTransfer->requirePriceDimension();

            if ($priceProductFilterTransfer->getPriceDimension()->getType() !== $priceProductTransfer->getPriceDimension()->getType()) {
                return false;
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
