<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Request;

interface MerchantRelationshipRequestDataInterface
{
    /**
     * @var string
     */
    public const KEY_ID = 'id';

    /**
     * @var string
     */
    public const KEY_MERCHANT_REFERENCE = 'merchantReference';

    /**
     * @var string
     */
    public const KEY_ID_COMPANY = 'idCompany';

    /**
     * @var string
     */
    public const KEY_ID_BUSINESS_UNIT_OWNER = 'idBusinessUnitOwner';

    /**
     * @var string
     */
    public const KEY_ASSIGNED_BUSINESS_UNITS = 'assignedBusinessUnits';

    /**
     * @var string
     */
    public const KEY_ID_COMPANY_BUSINESS_UNIT = 'idCompanyBusinessUnit';

    /**
     * @var string
     */
    public const KEY_ASSIGNED_PRODUCT_LISTS = 'assignedProductLists';

    /**
     * @var string
     */
    public const KEY_ID_PRODUCT_LIST = 'idProductList';
}
