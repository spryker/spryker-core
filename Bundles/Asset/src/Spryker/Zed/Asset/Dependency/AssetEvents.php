<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Dependency;

class AssetEvents
{
    /**
     * Specification:
     * - This events will be used for spy_asset entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_CREATE = 'Entity.spy_asset.create';

    /**
     * Specification:
     * - This events will be used for spy_asset entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_UPDATE = 'Entity.spy_asset.update';

    /**
     * Specification:
     * - This events will be used for spy_asset entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_DELETE = 'Entity.spy_asset.delete';

    /**
     * Specification:
     * - This events will be used for spy_asset_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_STORE_CREATE = 'Entity.spy_asset_store.create';

    /**
     * Specification:
     * - This events will be used for spy_asset_store entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_STORE_DELETE = 'Entity.spy_asset_store.delete';
}
