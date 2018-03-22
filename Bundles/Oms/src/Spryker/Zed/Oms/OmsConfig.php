<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms;

use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class OmsConfig extends AbstractBundleConfig
{
    const DEFAULT_PROCESS_LOCATION = APPLICATION_ROOT_DIR . '/config/Zed/oms';

    /**
     * @return string
     */
    public function getProcessDefinitionLocation()
    {
        return $this->get(OmsConstants::PROCESS_LOCATION, self::DEFAULT_PROCESS_LOCATION);
    }

    /**
     * @return array
     */
    public function getActiveProcesses()
    {
        return $this->get(OmsConstants::ACTIVE_PROCESSES, []);
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
     * @return string[]
     */
    public function getStateBlacklist()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getStateMachineLockerTimeoutInterval()
    {
        return '1 minutes';
    }

    /**
     * @return string
     */
    public function getSubProcessPrefixDelimiter()
    {
        return ' - ';
    }

    /**
     * @return string
     */
    public function getInitialStatus()
    {
        return 'new';
    }
}
