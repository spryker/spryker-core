<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Dependency\Facade;

interface MerchantProductDataImportToLocaleFacadeInterface
{
    /**
     * @return array<int, string>
     */
    public function getAvailableLocales(): array;
}
