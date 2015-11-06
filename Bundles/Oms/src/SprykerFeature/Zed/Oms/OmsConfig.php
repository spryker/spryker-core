<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
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
     * @return string|null
     */
    public function selectProcess(OrderTransfer $orderTransfer)
    {
        return null;
    }

}
