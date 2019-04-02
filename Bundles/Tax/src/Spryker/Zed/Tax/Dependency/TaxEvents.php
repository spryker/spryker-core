<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Dependency;

interface TaxEvents
{
    /**
     * Specification
     *  - This event will be used for tax_set publishing.
     *
     * @api
     */
    public const TAX_SET_PUBLISH = 'Entity.spy_tax_set.publish';

    /**
     * Specification:
     *  - This event will be used for tax_set entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_SET_CREATE = 'Entity.spy_tax_set.create';

    /**
     * Specification:
     * - This event will be used for tax_set entity update.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_SET_UPDATE = 'Entity.spy_tax_set.update';

    /**
     * Specification:
     * - This event will be used for tax_set entity delete.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_SET_DELETE = 'Entity.spy_tax_set.delete';

    /**
     * Specification:
     * - This event will be used for tax_rate entity update.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_RATE_UPDATE = 'Entity.spy_tax_rate.update';

    /**
     * Specification:
     * - This event will be used for tax_rate entity delete.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_RATE_DELETE = 'Entity.spy_tax_rate.delete';

    /**
     * Specification:
     *  - This event will be used for tax_set_tax entity creation.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_SET_TAX_CREATE = 'Entity.spy_tax_set_tax.create';

    /**
     * Specification:
     * - This event will be used for tax_set_tax entity delete.
     *
     * @api
     */
    public const ENTITY_SPY_TAX_SET_TAX_DELETE = 'Entity.spy_tax_set_tax.delete';
}
