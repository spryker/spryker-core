<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider;

use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetFilterForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspAssetFilterFormDataProvider
{
    public function __construct(protected SelfServicePortalConfig $selfServicePortalConfig)
    {
    }

    /**
     * @return array<mixed>
     */
    public function getOptions(): array
    {
        return [
            SspAssetFilterForm::OPTION_STATUSES => array_flip($this->selfServicePortalConfig->getAssetStatuses()),
        ];
    }
}
