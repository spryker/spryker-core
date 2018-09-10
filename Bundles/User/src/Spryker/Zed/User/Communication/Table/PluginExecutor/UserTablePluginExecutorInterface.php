<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface UserTablePluginExecutorInterface
{
    /**
     * @param array $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeActionButtonExpanderPlugins(array $user): array;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeConfigExpanderPlugins(TableConfiguration $tableConfiguration): TableConfiguration;

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeDataExpanderPlugins(array $item): array;
}
