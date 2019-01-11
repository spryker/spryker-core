<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface;
use Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToTaxServiceInterface;

class TaxRateCalculatorStrategyResolver implements TaxRateCalculatorStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Service\SalesToSalesServiceInterface
     */
    protected $service;

    /**
     * @var \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Spryker\Zed\ProductOption\Dependency\Service\ProductOptionToTaxServiceInterface $service
     * @param array|\Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface[] $strategyContainer
     */
    public function __construct(ProductOptionToTaxServiceInterface $service, array $strategyContainer)
    {
        $this->service = $service;
        $this->strategyContainer = $strategyContainer;

        $this->assertRequiredStrategyContainerItems();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface
     */
    public function resolveByQuote(QuoteTransfer $quoteTransfer): CalculatorInterface
    {
        if ($this->service->checkQuoteItemHasOwnShipmentTransfer($quoteTransfer) === false) {
            return $this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT];
        }

        return $this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT];
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function assertRequiredStrategyContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }

        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}