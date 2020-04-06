<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitSalesConnectorConfig extends AbstractBundleConfig
{
    public const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::SEARCH_TYPE_ALL
     */
    public const FILTER_FIELD_TYPE_ALL = 'all';

    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::FILTER_FIELD_TYPE_ORDER_BY
     */
    public const FILTER_FIELD_TYPE_ORDER_BY = 'orderBy';

    /**
     * @uses \Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig::FILTER_FIELD_TYPE_COMPANY
     */
    public const FILTER_FIELD_TYPE_COMPANY = 'company';
}
