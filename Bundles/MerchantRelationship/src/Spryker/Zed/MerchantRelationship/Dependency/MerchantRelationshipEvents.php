<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Dependency;

interface MerchantRelationshipEvents
{
    /**
     * Specification:
     * - This event will be used for spy_merchant_relationship_to_company_business_unit entity creation
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT_CREATE = 'Entity.spy_merchant_relationship_to_company_business_unit.create';

    /**
     * Specification:
     * - This event will be used for spy_merchant_relationship_to_company_business_unit entity update
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT_UPDATE = 'Entity.spy_merchant_relationship_to_company_business_unit.update';

    /**
     * Specification:
     * - This event will be used for spy_merchant_relationship_to_company_business_unit entity delete
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT_DELETE = 'Entity.spy_merchant_relationship_to_company_business_unit.delete';
}
