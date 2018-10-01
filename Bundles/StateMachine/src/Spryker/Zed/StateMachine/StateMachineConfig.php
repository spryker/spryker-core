<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StateMachineConfig extends AbstractBundleConfig
{
    public const GRAPH_NAME = 'Statemachine';

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
     * @return string
     */
    public function getStateMachineItemLockExpirationInterval()
    {
        return '1 minutes';
    }

    /**
     * @return string
     */
    public function getPathToStateMachineXmlFiles()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'Zed' . DIRECTORY_SEPARATOR . 'StateMachine';
    }

    /**
     * @return string
     */
    public function getGraphDefaultFormat()
    {
        return 'svg';
    }

    /**
     * @return string
     */
    public function getGraphDefaultFontSize()
    {
        return '14';
    }

    /**
     * @return string[]
     */
    public function getGraphFormatContentTypes()
    {
        return [
            'jpg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
        ];
    }

    /**
     * @return string
     */
    public function getSubProcessPrefixDelimiter()
    {
        return ' - ';
    }
}
