<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\GuiTableConfigInterface;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GuiTableConfig extends AbstractBundleConfig implements GuiTableConfigInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getDefaultDataSourceType(): string
    {
        return 'http';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getDefaultEnabledFeatures(): array
    {
        return [
            GuiTableConfigurationTransfer::PAGINATION,
            GuiTableConfigurationTransfer::FILTERS,
            GuiTableConfigurationTransfer::SEARCH,
            GuiTableConfigurationTransfer::ROW_ACTIONS,
            GuiTableConfigurationTransfer::SYNC_STATE_URL,
            GuiTableConfigurationTransfer::SETTINGS,
        ];
    }

    /**
     * @api
     *
     * @return int[]
     */
    public function getDefaultAvailablePageSizes(): array
    {
        return [10, 25, 50, 100];
    }

    /**
     * @api
     *
     * @return int
     */
    public function getDefaultPageSize(): int
    {
        return 10;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultSearchPlaceholder(): string
    {
        return 'Search';
    }
}
