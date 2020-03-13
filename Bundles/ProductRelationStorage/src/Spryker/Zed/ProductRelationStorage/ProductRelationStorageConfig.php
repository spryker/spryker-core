<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductRelationStorageConfig extends AbstractBundleConfig
{
    public const ENTITY_SPY_PRODUCT_RELATION_CREATE = 'Entity.spy_product_relation.create';
    public const ENTITY_SPY_PRODUCT_RELATION_UPDATE = 'Entity.spy_product_relation.update';
    public const ENTITY_SPY_PRODUCT_RELATION_DELETE = 'Entity.spy_product_relation.delete';

    public const PRODUCT_ABSTRACT_RELATION_PUBLISH = 'ProductRelation.product_abstract_relation.publish';
    public const PRODUCT_ABSTRACT_RELATION_UNPUBLISH = 'ProductRelation.product_abstract_relation.unpublish';

    public const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_relation_product_abstract.create';
    public const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_relation_product_abstract.update';
    public const ENTITY_SPY_PRODUCT_RELATION_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_relation_product_abstract.delete';

    public const ENTITY_SPY_PRODUCT_RELATION_STORE_CREATE = 'Entity.spy_product_relation_store.create';
    public const ENTITY_SPY_PRODUCT_RELATION_STORE_DELETE = 'Entity.spy_product_relation_store.delete';

    /**
     * @api
     *
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductAbstractRelationSynchronizationPoolName(): ?string
    {
        return null;
    }
}
