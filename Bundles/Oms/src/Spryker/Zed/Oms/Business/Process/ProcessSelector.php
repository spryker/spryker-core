<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Process;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Oms\OmsConfig;

class ProcessSelector
{

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
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
