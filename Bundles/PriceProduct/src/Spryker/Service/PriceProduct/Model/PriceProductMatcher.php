<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    /**
     * @var \Spryker\Service\PriceProduct\Model\PriceProductCriteriaBuilderInterface
     */
    protected $priceProductCriteriaBuilder;

    /**
     * @var \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected $priceProductDecisionPlugins = [];

    /**
     * @param \Spryker\Service\PriceProduct\Model\PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder
     * @param \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[] $priceProductDecisionPlugins
     */
    public function __construct(PriceProductCriteriaBuilderInterface $priceProductCriteriaBuilder, array $priceProductDecisionPlugins)
    {
        $this->priceProductCriteriaBuilder = $priceProductCriteriaBuilder;
        $this->priceProductDecisionPlugins = $priceProductDecisionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function matchPriceValue(
        array $priceProductTransferCollection,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): ?int {

        if (count($priceProductTransferCollection) === 0) {
            return null;
        }

        $priceProductCriteriaTransfer = $this->preparePriceProductCriteria($priceProductCriteriaTransfer);

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $moneyValueTransfer = $priceProductDecisionPlugin->matchValue($priceProductTransferCollection, $priceProductCriteriaTransfer);
            if ($moneyValueTransfer) {
                return $this->findPriceValueByCriteria($moneyValueTransfer, $priceProductCriteriaTransfer);
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function preparePriceProductCriteria(?PriceProductCriteriaTransfer $priceProductCriteriaTransfer): PriceProductCriteriaTransfer
    {
        if ($priceProductCriteriaTransfer === null) {
            return $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues();
        }

        $defaultProductCriteriaTransfer = $this->priceProductCriteriaBuilder->buildCriteriaWithDefaultValues();

        if ($priceProductCriteriaTransfer->getIdStore() === null) {
            $priceProductCriteriaTransfer->setIdStore($defaultProductCriteriaTransfer->getIdStore());
        }

        if ($priceProductCriteriaTransfer->getIdCurrency() === null) {
            $priceProductCriteriaTransfer->setIdCurrency($defaultProductCriteriaTransfer->getIdCurrency());
        }

        if ($priceProductCriteriaTransfer->getPriceMode() === null) {
            $priceProductCriteriaTransfer->setPriceMode($defaultProductCriteriaTransfer->getPriceMode());
        }

        if ($priceProductCriteriaTransfer->getPriceType() === null) {
            $priceProductCriteriaTransfer->setPriceType($defaultProductCriteriaTransfer->getPriceType());
        }

        return $priceProductCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    protected function findPriceValueByCriteria(MoneyValueTransfer $moneyValueTransfer, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?int
    {
        if ($priceProductCriteriaTransfer->getPriceMode() === 'GROSS_MODE') {
            return $moneyValueTransfer->getGrossAmount();
        }

        return $moneyValueTransfer->getNetAmount();
    }
}
