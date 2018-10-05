<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Dependency;

interface ProductDiscontinuedEvents
{
    /**
     * Specification
     * - This events will be used for spy_product_discontinued entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_DISCONTINUED_CREATE = 'Entity.spy_product_discontinued.create';

    /**
     * Specification
     * - This events will be used for spy_product_discontinued entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_DISCONTINUED_UPDATE = 'Entity.spy_product_discontinued.update';

    /**
     * Specification
     * - This events will be used for spy_product_discontinued entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_DISCONTINUED_DELETE = 'Entity.spy_product_discontinued.delete';

    /**
     * Specification
     * - This events will be used for spy_product_discontinued_note entity creation
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_CREATE = 'Entity.spy_product_discontinued_note.create';

    /**
     * Specification
     * - This events will be used for spy_product_discontinued_note entity changes
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_UPDATE = 'Entity.spy_product_discontinued_note.update';

    /**
     * Specification
     * - This events will be used for spy_product_discontinued_note entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_PRODUCT_DISCONTINUED_NOTE_DELETE = 'Entity.spy_product_discontinued_note.delete';

    /**
     * Specification:
     * - This event is used for product_discontinued publishing.
     *
     * @api
     */
    public const PRODUCT_DISCONTINUED_PUBLISH = 'ProductDiscontinued.product_discontinued.publish';

    /**
     * Specification:
     * - This event is used for product_discontinued unpublishing.
     *
     * @api
     */
    public const PRODUCT_DISCONTINUED_UNPUBLISH = 'ProductDiscontinued.product_discontinued.unpublish';
}
