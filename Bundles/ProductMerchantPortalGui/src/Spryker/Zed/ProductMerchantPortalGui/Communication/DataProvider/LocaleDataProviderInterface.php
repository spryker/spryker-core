<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;


interface LocaleDataProviderInterface
{
    /**
     * @return string|null
     */
    public function findDefaultStoreDefaultLocale(): ?string;
}