<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider;

interface MerchantAccountFormDataProviderInterface
{
    /**
     * @return mixed[]
     */
    public function getOptions(): array;

    /**
     * @return mixed[]
     */
    public function getData(): array;
}
