<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OmsConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getProcessDefinitionLocation()
    {
        return APPLICATION_ROOT_DIR . OmsConstants::DEFAULT_PROCESS_LOCATION;
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

    /**
     * @return string[]
     */
    public function getStateBlacklist()
    {
        return [];
    }

}
