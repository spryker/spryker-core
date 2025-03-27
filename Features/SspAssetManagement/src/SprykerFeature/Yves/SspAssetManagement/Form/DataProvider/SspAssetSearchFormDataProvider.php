<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Form\DataProvider;

use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetSearchFiltersForm;

class SspAssetSearchFormDataProvider
{
    /**
     * @var array<string, string>
     */
    protected const SCOPE_OPTIONS = [
        'customer.ssp_asset.filter_by_business_unit' => 'filterByBusinessUnit',
        'customer.ssp_asset.filter_by_company' => 'filterByCompany',
    ];

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            SspAssetSearchFiltersForm::SCOPE_OPTIONS => static::SCOPE_OPTIONS,
        ];
    }
}
