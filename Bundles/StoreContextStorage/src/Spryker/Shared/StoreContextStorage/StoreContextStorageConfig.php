<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\StoreContextStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class StoreContextStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification
     * - This event will be used for `spy_store_context` entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STORE_CONTEXT_CREATE = 'Entity.spy_store_context.create';

    /**
     * Specification
     * - This event will be used for `spy_store_context` entity updating.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STORE_CONTEXT_UPDATE = 'Entity.spy_store_context.update';

    /**
     * Specification
     * - This event will be used for `spy_store_context` entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STORE_CONTEXT_DELETE = 'Entity.spy_store_context.delete';
}
