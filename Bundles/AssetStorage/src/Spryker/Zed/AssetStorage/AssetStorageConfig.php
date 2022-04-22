<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AssetStorageConfig extends AbstractBundleConfig
{
    /**
     * The column name for the asset_slot field.
     *
     * @var string
     */
    public const COL_ASSET_SLOT = 'spy_asset.asset_slot';

    /**
     * The column name for the fk_asset field.
     *
     * @var string
     */
    public const COL_FK_ASSET = 'spy_asset_store.fk_asset';

    /**
     * The column name for the fk_store field.
     *
     * @var string
     */
    public const COL_FK_STORE = 'spy_asset_store.fk_store';

    /**
     * @api
     *
     * @return string|null
     */
    public function findEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function findSynchronizationPoolName(): ?string
    {
        return null;
    }
}
