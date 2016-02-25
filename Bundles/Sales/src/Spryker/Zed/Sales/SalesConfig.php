<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesConfig extends AbstractBundleConfig
{

    const PARAM_IS_SALES_ORDER = 'id-sales-order';
    const TEST_CUSTOMER_FIRST_NAME = 'test order';

    /**
     * TODO Not needed, remove
     *
     * @var array|string[]
     */
    protected static $stateMachineMapper = [
        'invoice' => 'Invoice01',
        'no_payment' => 'Nopayment01',
    ];
    /**
     * TODO FW Not used. Please remove.
     *
     * @return string
     */
    public function getInvoiceIncrementPrefix()
    {
        return '3';
    }

    /**
     * TODO FW Not used. Please remove.
     *
     * @return string
     */
    public function getInvoiceIncrementDivider()
    {
        return '-';
    }

    /**
     * TODO FW Not used. Please remove.
     *
     * total count of digits in invoiceNumber including prefix (max 20)
     *
     * @return int
     */
    public function getInvoiceIncrementDigits()
    {
        return 16;
    }

    /**
     * TODO FW Not used. Please remove.
     *
     * minimum incrementation of invoice number
     *
     * @return int
     */
    public function getInvoiceIncrementMin()
    {
        return 1;
    }

    /**
     * TODO FW Not used. Please remove.
     *
     * maximum incrementation of invoice number
     *
     * @return int
     */
    public function getInvoiceIncrementMax()
    {
        return 5;
    }

    /**
     * TODO FW Not used. Please remove.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function getStateMachineTriggerQueueProcessMessageAmount()
    {
        return 50;
    }

    /**
     * Separator for the sequence number
     * @return string
     */
    public function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
     * TODO FW Move the whole algortithm to the bundle config
     *
     * OR-condition
     *
     * @return array
     */
    public function getMarkAsTestConditions()
    {
        return [
            'last_name' => 'Tester',
        ];
    }

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(SalesConstants::NAME_ORDER_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = Store::getInstance()->getStoreName();
        $sequenceNumberPrefixParts[] = $this->get(SalesConstants::ENVIRONMENT_PREFIX);
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isTestOrder(QuoteTransfer $quoteTransfer)
    {
        $shippingAddressTransfer = $quoteTransfer->getShippingAddress();

        if ($shippingAddressTransfer === null || $shippingAddressTransfer->getFirstName() !== self::TEST_CUSTOMER_FIRST_NAME) {
            return false;
        }

        return true;
    }

    /**
     * This method determines state machine process from the given quote transfer and order item.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function determineProcessForOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        throw new \BadMethodCallException('You need to provide at least one state machine process for given method!');
    }

}
