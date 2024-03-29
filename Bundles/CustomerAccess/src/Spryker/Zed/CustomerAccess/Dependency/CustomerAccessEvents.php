<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Dependency;

interface CustomerAccessEvents
{
    /**
     * Specification
     * - This event will be used for spy_unauthenticated_customer_access entity changes
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_UPDATE = 'Entity.spy_unauthenticated_customer_access.update';

    /**
     * Specification
     * - This event will be used for spy_unauthenticated_customer_access entity created
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_CREATE = 'Entity.spy_unauthenticated_customer_access.create';

    /**
     * Specification
     * - This event will be used for spy_unauthenticated_customer_access entity deletion
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_DELETE = 'Entity.spy_unauthenticated_customer_access.delete';
}
