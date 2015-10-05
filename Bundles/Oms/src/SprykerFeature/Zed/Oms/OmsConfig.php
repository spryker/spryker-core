<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class OmsConfig extends AbstractBundleConfig
{

    const INITIAL_STATUS = 'new';
    const DEFAULT_PROCESS_LOCATION = '/config/Zed/oms';
    const NAME_CREDIT_MEMO_REFERENCE = 'CreditMemoReference';

    /**
     * @return string
     */
    public function getProcessDefinitionLocation()
    {
        return APPLICATION_ROOT_DIR . self::DEFAULT_PROCESS_LOCATION;
    }

    /**
     * @return array
     */
    public function getActiveProcesses()
    {
        return [];
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return mixed
     */
    public function selectProcess(OrderTransfer $orderTransfer)
    {
        return;
    }

    public function getCreditMemoReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(self::NAME_CREDIT_MEMO_REFERENCE);

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
        $environment = \SprykerFeature_Shared_Library_Environment::getInstance();

        if ($environment->isStaging()) {
            return 'S';
        }

        if ($environment->isDevelopment()) {
            return 'D' . $this->getUniqueIdentifierSeparator() . $this->getTimestamp();
        }

        return 'P';
    }

    /**
     * @return string
     */
    protected function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
     * @return string
     */
    protected function getTimestamp()
    {
        $ts = strtr(microtime(), [
            '.' => '',
            ' ' => '',
        ]);

        return $ts;
    }

}
