<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class UserTablePluginExecutor implements UserTablePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface[]
     */
    protected $userTableActionExpanderPlugins;

    /**
     * @var \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface[]
     */
    protected $userTableConfigExpanderPlugins;

    /**
     * @var \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface[]
     */
    protected $userTableDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableActionExpanderPluginInterface[] $userTableActionExpanderPlugins
     * @param \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableConfigExpanderPluginInterface[] $userTableConfigExpanderPlugins
     * @param \Spryker\Zed\UserExtension\Dependency\Plugin\UserTableDataExpanderPluginInterface[] $userTableDataExpanderPlugins
     */
    public function __construct(
        array $userTableActionExpanderPlugins,
        array $userTableConfigExpanderPlugins,
        array $userTableDataExpanderPlugins
    ) {
        $this->userTableActionExpanderPlugins = $userTableActionExpanderPlugins;
        $this->userTableConfigExpanderPlugins = $userTableConfigExpanderPlugins;
        $this->userTableDataExpanderPlugins = $userTableDataExpanderPlugins;
    }

    /**
     * @param array $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeActionButtonExpanderPlugins(array $user): array
    {
        $buttonTransfers = [];
        foreach ($this->userTableActionExpanderPlugins as $usersTableExpanderPlugin) {
            $buttonTransfers = array_merge($buttonTransfers, $usersTableExpanderPlugin->getActionButtonDefinitions($user));
        }

        return $buttonTransfers;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeConfigExpanderPlugins(TableConfiguration $tableConfiguration): TableConfiguration
    {
        foreach ($this->userTableConfigExpanderPlugins as $userTableConfigExpanderPlugin) {
            $tableConfiguration = $userTableConfigExpanderPlugin->expandConfig($tableConfiguration);
        }

        return $tableConfiguration;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeDataExpanderPlugins(array $item): array
    {
        $data = [];
        foreach ($this->userTableDataExpanderPlugins as $userTableDataExpanderPlugin) {
            $data = array_merge($data, $userTableDataExpanderPlugin->expandData($item));
        }

        return $data;
    }
}
