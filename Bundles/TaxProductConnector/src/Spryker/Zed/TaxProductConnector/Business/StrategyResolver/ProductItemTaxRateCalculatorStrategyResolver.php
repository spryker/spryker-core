<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Tax\Business\Model\CalculatorInterface;
use Spryker\Zed\TaxProductConnector\Dependency\Service\TaxProductConnectorToTaxServiceInterface;

class ProductItemTaxRateCalculatorStrategyResolver implements ProductItemTaxRateCalculatorStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Service\SalesToSalesServiceInterface
     */
    protected $service;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverInterface[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param \Spryker\Zed\TaxProductConnector\Dependency\Service\TaxProductConnectorToTaxServiceInterface $service
     * @param array|\Spryker\Zed\Tax\Business\Model\CalculatorInterface[] $strategyContainer
     */
    public function __construct(TaxProductConnectorToTaxServiceInterface $service, array $strategyContainer)
    {
        $this->service = $service;
        $this->strategyContainer = $strategyContainer;

        $this->assertRequiredStrategyContainerItems();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Tax\Business\Model\CalculatorInterface
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