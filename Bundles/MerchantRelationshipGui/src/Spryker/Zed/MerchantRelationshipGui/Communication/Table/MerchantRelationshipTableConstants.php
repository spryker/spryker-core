<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Table;

use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;

interface MerchantRelationshipTableConstants
{
    public const COL_ID_MERCHANT_RELATIONSHIP = SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP;
    public const COL_MERCHANT_NAME = 'merchant_name';
    public const COL_MERCHANT_ID = 'merchant_id';
    public const COL_BUSINESS_UNIT_OWNER = 'business_unit_owner';
    public const COL_ASSIGNED_BUSINESS_UNITS = 'asssigned_business_units';
    public const COL_ACTIONS = 'actions';

    public const REQUEST_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

    public const URL_MERCHANT_RELATIONSHIP_LIST = '/merchant-relationship-gui/list-merchant-relationship';
    public const URL_MERCHANT_RELATIONSHIP_EDIT = '/merchant-relationship-gui/edit-merchant-relationship';
    public const URL_MERCHANT_RELATIONSHIP_DELETE = '/merchant-relationship-gui/delete-merchant-relationship';
}
