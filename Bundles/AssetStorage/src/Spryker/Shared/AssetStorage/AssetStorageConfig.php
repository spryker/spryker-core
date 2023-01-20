<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AssetStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class AssetStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Key generation resource name for asset messages.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_SLOT_RESOURCE_NAME = 'asset_slot';

    /**
     * Specification:
     *  - Queue name as used for processing asset messages.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_SYNC_STORAGE_QUEUE = 'sync.storage.asset_slot';

    /**
     * Specification:
     * - This event is used for asset publishing.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_PUBLISH = 'Asset.publish';

    /**
     * Specification:
     * - Asset resource name, used for key generating.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_RESOURCE_NAME = 'asset';
}
