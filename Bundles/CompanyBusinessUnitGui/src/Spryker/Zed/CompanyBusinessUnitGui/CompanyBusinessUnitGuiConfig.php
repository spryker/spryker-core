<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitGuiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig
     * @var string
     */
    public const COL_ID_COMPANY_USER = 'id_company_user';

    /**
     * @var int
     */
    protected const COMPANY_BUSINESS_UNIT_SUGGESTION_LIMIT = 20;

    /**
     * @api
     *
     * @return int
     */
    public function getCompanyBusinessUnitSuggestionLimit(): int
    {
        return static::COMPANY_BUSINESS_UNIT_SUGGESTION_LIMIT;
    }
}
