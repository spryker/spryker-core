<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Table;

use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;

interface MerchantTableConstants
{
    public const COL_ID_MERCHANT = SpyMerchantTableMap::COL_ID_MERCHANT;
    public const COL_NAME = SpyMerchantTableMap::COL_NAME;
    public const COL_STATUS = SpyMerchantTableMap::COL_STATUS;
    public const COL_ACTIONS = 'actions';

    public const REQUEST_ID_MERCHANT = 'id-merchant';

    public const URL_MERCHANT_LIST = '/merchant-gui/list-merchant';
    public const URL_MERCHANT_EDIT = '/merchant-gui/edit-merchant';
    public const URL_MERCHANT_STATUS = '/merchant-gui/status-merchant';
}
