<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface MerchantTablePluginExecutorInterface
{
    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeActionButtonExpanderPlugins(array $item): array;

    /**
     * @return array
     */
    public function executeTableHeaderExpanderPlugins(): array;

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeDataExpanderPlugins(array $item): array;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeConfigExpanderPlugins(TableConfiguration $tableConfiguration): TableConfiguration;
}
