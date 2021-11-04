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

    /**
     * @var string
     */
    public const COL_MERCHANT_NAME = 'merchant_name';

    /**
     * @var string
     */
    public const COL_MERCHANT_ID = 'merchant_id';

    /**
     * @var string
     */
    public const COL_BUSINESS_UNIT_OWNER = 'business_unit_owner';

    /**
     * @var string
     */
    public const COL_ASSIGNED_BUSINESS_UNITS = 'asssigned_business_units';

    /**
     * @var string
     */
    public const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    public const REQUEST_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

    /**
     * @var string
     */
    public const URL_MERCHANT_RELATIONSHIP_LIST = '/merchant-relationship-gui/list-merchant-relationship';

    /**
     * @var string
     */
    public const URL_MERCHANT_RELATIONSHIP_EDIT = '/merchant-relationship-gui/edit-merchant-relationship';

    /**
     * @var string
     */
    public const URL_MERCHANT_RELATIONSHIP_DELETE = '/merchant-relationship-gui/delete-merchant-relationship';
}
