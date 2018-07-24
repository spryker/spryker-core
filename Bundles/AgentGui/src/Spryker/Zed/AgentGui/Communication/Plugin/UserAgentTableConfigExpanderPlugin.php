<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentGui\Communication\Plugin;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface;

class UserAgentTableConfigExpanderPlugin extends AbstractPlugin implements UserTableConfigExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $this->addAgentHeader($config);
        $this->setRawAgentColumn($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function addAgentHeader(TableConfiguration $config): void
    {
        $config->setHeader(array_merge($config->getHeader(), [
            SpyUserTableMap::COL_IS_AGENT => 'Agent',
        ]));
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawAgentColumn(TableConfiguration $config): void
    {
        $config->setRawColumns(array_merge($config->getRawColumns(), [
            SpyUserTableMap::COL_IS_AGENT,
        ]));
    }
}
