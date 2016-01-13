<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\SequenceNumber\SequenceNumberConstants;

class SalesConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getInvoiceIncrementPrefix()
    {
        return '3';
    }

    /**
     * @return string
     */
    public function getInvoiceIncrementDivider()
    {
        return '-';
    }

    /**
     * total count of digits in invoiceNumber including prefix (max 20)
     *
     * @return int
     */
    public function getInvoiceIncrementDigits()
    {
        return 16;
    }

    /**
     * minimum incrementation of invoice number
     *
     * @return int
     */
    public function getInvoiceIncrementMin()
    {
        return 1;
    }

    /**
     * maximum incrementation of invoice number
     *
     * @return int
     */
    public function getInvoiceIncrementMax()
    {
        return 5;
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getOrderIncrementKeys()
    {
        return ['2', '5', '9', '3', '8', '1', '7', '6', '4'];
    }

    /**
     * @deprecated
     *
     * @return array
     */
    public function getOrderIncrementPrefix()
    {
        return 6;
    }

    /**
     * @deprecated
     *
     * @return int
     */
    public function getOrderIncrementDigits()
    {
        return 11;
    }

    /**
     * @throws \Exception
     *
     * @return int
     */
    public function getStateMachineTriggerQueueProcessMessageAmount()
    {
        return 50;
    }

    /**
     * @return string
     */
    public function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
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
     * @return SequenceNumberSettingsTransfer
     */
    public function getOrderReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(SalesConstants::NAME_ORDER_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = Store::getInstance()->getStoreName();
        $sequenceNumberPrefixParts[] = $this->get(SequenceNumberConstants::ENVIRONMENT_PREFIX);
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

}
