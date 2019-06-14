<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface ProductListTablePluginExecutorInterface
{
    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeTableActionExpanderPlugins(array $item): array;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeTableConfigExpanderPlugins(TableConfiguration $config): TableConfiguration;

    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function executeTableQueryCriteriaExpanderPlugins(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer;

    /**
     * @return array
     */
    public function executeTableHeaderExpanderPlugins(): array;

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeTableDataExpanderPlugins(array $item): array;
}
