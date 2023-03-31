<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\StoreStorage;

class StoreStorageConfig
{
    /**
     * Specification
     * - These events will be used for spy_store publishing.
     *
     * @api
     *
     * @var string
     */
    public const STORE_PUBLISH_WRITE = 'Store.store.publish';

    /**
     * Specification
     * - These events will be used for spy_store unpublishing.
     *
     * @api
     *
     * @var string
     */
    public const STORE_PUBLISH_DELETE = 'Store.store.unpublish';

    /**
     * Specification
     * - These events will be used for spy_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STORE_CREATE = 'Entity.spy_store.create';

    /**
     * Specification
     * - These events will be used for spy_store entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_STORE_UPDATE = 'Entity.spy_store.update';

    /**
     * Specification
     * - These events will be used for spy_locale_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_LOCALE_STORE_CREATE = 'Entity.spy_locale_store.create';

    /**
     * Specification
     * - These events will be used for spy_locale_store entity deletions.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_LOCALE_STORE_DELETE = 'Entity.spy_locale_store.delete';

    /**
     * Specification
     * - These events will be used for spy_currency_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_CURRENCY_STORE_CREATE = 'Entity.spy_currency_store.create';

    /**
     * Specification
     * - These events will be used for spy_currency_store entity deletions.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_CURRENCY_STORE_DELETE = 'Entity.spy_currency_store.delete';

    /**
     * Specification
     * - These events will be used for spy_country_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_COUNTRY_STORE_CREATE = 'Entity.spy_country_store.create';

    /**
     * Specification
     * - These events will be used for spy_country_store entity deletions.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_COUNTRY_STORE_DELETE = 'Entity.spy_country_store.delete';

    /**
     * Specification:
     * - Queue name as used for processing store messages.
     *
     * @api
     *
     * @var string
     */
    public const STORE_SYNC_STORAGE_QUEUE = 'sync.storage.store';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     *
     * @var string
     */
    public const STORE_RESOURCE_NAME = 'store';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     *
     * @var string
     */
    public const STORE_LIST_RESOURCE_NAME = 'store_list';
}
