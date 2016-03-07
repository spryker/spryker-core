<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return array
     */
    public function getGraphDefaults()
    {
        return [
            'fontname' => 'Verdana',
            'labelfontname' => 'Verdana',
            'nodesep' => 0.6,
            'ranksep' => 0.8,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
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
