<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SequenceNumber\SequenceNumberConstants;

class SalesConfig extends AbstractBundleConfig
{

    const NAME_ORDER_REFERENCE = 'OrderReference';

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
    public function getInvoiceIncrementDevider()
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
        return  [
            'last_name' => 'Tester',
        ];
    }

    /**
     * @return SequenceNumberSettingsInterface
     */
    public function getOrderReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(self::NAME_ORDER_REFERENCE);

        $storeName = Store::getInstance()->getStoreName();
        $prefix = $storeName . $this->getUniqueIdentifierSeparator() . $this->getEnvironmentPrefix();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * @return string
     */
    protected function getEnvironmentPrefix()
    {
        return $this->get(SequenceNumberConstants::ENVIRONMENT_PREFIX);
    }

    /**
     * @return string
     */
    protected function getTimestamp()
    {
        return (string) time();
    }

}
