<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitSalesConnectorConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ALL
     */
    public const FILTER_FIELD_TYPE_ALL = 'all';

    public const FILTER_FIELD_TYPE_ORDER_BY = 'orderBy';

    public const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';
}
