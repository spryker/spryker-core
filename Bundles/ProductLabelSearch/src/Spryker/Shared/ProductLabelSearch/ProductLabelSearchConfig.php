<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductLabelSearch;

interface ProductLabelSearchConfig
{
    /**
     * Specification
     * - This events will be used for spy_product_label entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.spy_product_label.update';

    /**
     * Specification
     * - This events will be used for spy_product_label entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_DELETE = 'Entity.spy_product_label.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE = 'Entity.spy_product_label_product_abstract.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE = 'Entity.spy_product_label_product_abstract.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_product_abstract entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE = 'Entity.spy_product_label_product_abstract.delete';

    /**
     * Specification
     * - This events will be used for spy_product_label_store entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_CREATE = 'Entity.spy_product_label_store.create';

    /**
     * Specification
     * - This events will be used for spy_product_label_store entity changes.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_UPDATE = 'Entity.spy_product_label_store.update';

    /**
     * Specification
     * - This events will be used for spy_product_label_store entity deletion.
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_LABEL_STORE_DELETE = 'Entity.spy_product_label_store.delete';

    public const PLUGIN_PRODUCT_LABEL_DATA = 'PLUGIN_PRODUCT_LABEL_DATA';
}
