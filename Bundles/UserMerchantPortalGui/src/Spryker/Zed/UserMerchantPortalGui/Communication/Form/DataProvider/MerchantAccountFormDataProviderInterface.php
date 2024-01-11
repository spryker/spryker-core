<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui\Communication\Form\DataProvider;

interface MerchantAccountFormDataProviderInterface
{
    /**
     * @param bool $isEmailUniquenessValidationEnabled
     *
     * @return array<mixed>
     */
    public function getOptions(bool $isEmailUniquenessValidationEnabled = true): array;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
