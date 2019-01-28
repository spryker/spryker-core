<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Dependency;

interface CompanyUserEvents
{
    /**
     * Specification:
     * - This event will be used for spy_merchant_relationship_to_company_business_unit entity creation
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_USER_CREATE = 'Entity.spy_merchant_relationship_to_company_business_unit.create';

    /**
     * Specification:
     * - This event will be used for spy_merchant_relationship_to_company_business_unit entity update
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_USER_UPDATE = 'Entity.spy_merchant_relationship_to_company_business_unit.update';

    /**
     * Specification:
     * - This event will be used for spy_merchant_relationship_to_company_business_unit entity delete
     *
     * @api
     */
    public const ENTITY_SPY_COMPANY_USER_DELETE = 'Entity.spy_merchant_relationship_to_company_business_unit.delete';
}
