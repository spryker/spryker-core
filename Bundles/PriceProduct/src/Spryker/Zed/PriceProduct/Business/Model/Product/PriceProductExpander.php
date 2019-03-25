<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;
use Spryker\Shared\PriceProduct\PriceProductConstants;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class PriceProductExpander implements PriceProductExpanderInterface
{
    /**
     * @var \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface[]
     */
    protected $priceProductDimensionExpanderStrategyPlugins;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface[] $priceProductDimensionExpanderStrategyPlugins
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct(
        array $priceProductDimensionExpanderStrategyPlugins,
        PriceProductConfig $priceProductConfig,
        PriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductDimensionExpanderStrategyPlugins = $priceProductDimensionExpanderStrategyPlugins;
        $this->priceProductConfig = $priceProductConfig;
        $this->priceProductService = $priceProductService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function expandPriceProductTransfers(array $priceProductTransfers): array
    {
        $expandedPriceProductTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $expandedPriceProductTransfers[] = $this->expandPriceProductTransfer($priceProductTransfer);
        }

        return $expandedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function expandPriceProductTransfer(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceDimensionTransfer = $priceProductTransfer->getPriceDimension();
        $priceProductTransfer->setPriceDimension($this->expandPriceProductDimensionTransfer($priceDimensionTransfer));
        $priceProductTransfer->setGroupKey($this->priceProductService->buildPriceProductGroupKey($priceProductTransfer));

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected function expandPriceProductDimensionTransfer(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        foreach ($this->priceProductDimensionExpanderStrategyPlugins as $priceProductDimensionExpanderStrategyPlugin) {
            if ($priceProductDimensionExpanderStrategyPlugin->isApplicable($priceProductDimensionTransfer)) {
                return $priceProductDimensionExpanderStrategyPlugin->expand($priceProductDimensionTransfer);
            }
        }

        if ($priceProductDimensionTransfer->getIdPriceProductDefault() !== null) {
            $priceProductDimensionTransfer->setType(PriceProductConstants::PRICE_DIMENSION_DEFAULT);
            $priceProductDimensionTransfer->setName($this->priceProductConfig->getPriceDimensionDefaultName());
        }

        return $priceProductDimensionTransfer;
    }
}
