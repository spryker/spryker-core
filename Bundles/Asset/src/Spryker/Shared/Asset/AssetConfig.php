<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Asset;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class AssetConfig extends AbstractSharedConfig
{
    /**
     * Specification
     * - These event is used for asset un-publishing.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_UNPUBLISH = 'Asset.unpublish';

    /**
     * Specification
     * - These event is used for asset publishing.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_PUBLISH = 'Asset.publish';
}
