<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\QuoteApprovalShipmentConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Client\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorClientInterface;
use Spryker\Shared\Price\PriceConfig;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class QuoteApprovalShipmentConnectorClientTester extends Actor
{
    use _generated\QuoteApprovalShipmentConnectorClientTesterActions;

    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @return \Spryker\Client\QuoteApprovalShipmentConnector\QuoteApprovalShipmentConnectorClientInterface
     */
    public function getClient(): QuoteApprovalShipmentConnectorClientInterface
    {
        return $this->getLocator()->quoteApprovalShipmentConnector()->client();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithMultiShipment(): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder([
                QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_NET,
            ]))
            ->withItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder([ShipmentTransfer::SHIPMENT_SELECTION => 'custom']))
                            ->withShippingAddress()
                            ->withMethod()
                    )
            )
            ->withAnotherItem(
                (new ItemBuilder())
                    ->withShipment(
                        (new ShipmentBuilder([ShipmentTransfer::SHIPMENT_SELECTION => 'custom']))
                            ->withShippingAddress()
                            ->withMethod()
                    )
            )
            ->withBillingAddress()
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->build();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $quoteTransfer->addExpense(
                (new ExpenseTransfer())->setType(static::SHIPMENT_EXPENSE_TYPE)
                ->setShipment($itemTransfer->getShipment())
            );
        }

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithSingleShipment(): QuoteTransfer
    {
        return (new QuoteBuilder([
                QuoteTransfer::PRICE_MODE => PriceConfig::PRICE_MODE_NET,
            ]))
            ->withItem()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCustomer()
            ->withShipment([ShipmentTransfer::SHIPMENT_SELECTION => 'custom'])
            ->withTotals()
            ->withCurrency()
            ->build()
            ->addExpense((new ExpenseTransfer())->setType(static::SHIPMENT_EXPENSE_TYPE));
    }
}
