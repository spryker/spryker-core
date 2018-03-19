<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Dependency;

class CustomerAccessEvents
{
    /**
     * Specification
     * - This events will be used for spy_unauthenticated_customer_access entity changes
     *
     * @api
     */
    const ENTITY_SPY_UNAUTHENTICATED_CUSTOMER_ACCESS_UPDATE = 'Entity.spy_unauthenticated_customer_access.update';

    /**
     * Specification
     * - This events will be used for spy_unauthenticated_customer_access_abstract publishing
     *
     * @api
     */
    const UNAUTHENTICATED_CUSTOMER_ACCESS_ABSTRACT_PUBLISH = 'Entity.spy_unauthenticated_customer_access_abstract.publish';
}
