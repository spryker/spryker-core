<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider;

use SprykerFeature\Zed\SspAssetManagement\Communication\Form\SspAssetFilterForm;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig;

class SspAssetFilterFormDataProvider implements SspAssetFilterFormDataProviderInterface
{
    /**
     * @param \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig $sspAssetManagementConfig
     */
    public function __construct(protected SspAssetManagementConfig $sspAssetManagementConfig)
    {
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return [
            SspAssetFilterForm::OPTION_STATUSES => array_flip($this->sspAssetManagementConfig->getAssetStatuses()),
        ];
    }
}
