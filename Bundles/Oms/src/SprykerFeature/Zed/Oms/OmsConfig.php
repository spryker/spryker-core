<?php

namespace SprykerFeature\Zed\Oms;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class OmsConfig extends AbstractBundleConfig
{

    const INITIAL_STATUS = 'new';
    const DEFAULT_PROCESS_LOCATION = '/config/Zed/oms';

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
     * @param SalesOrderTransfer $orderTransfer
     *
     * @return mixed
     */
    public function selectProcess(SalesOrderTransfer $orderTransfer)
    {
        return null;
    }

    /**
     * @return CommandInterface[]
     */
    public function getCommands()
    {
        return [];
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions()
    {
        return [];
    }
}
