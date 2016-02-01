<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Oms\Business\Process;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Oms\OmsConfig;

class ProcessSelector
{

    /**
     * @var OmsConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Oms\OmsConfig $config
     */
    public function __construct(OmsConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $transferOrder
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $transferOrder)
    {
        return $this->config->selectProcess($transferOrder);
    }

}
