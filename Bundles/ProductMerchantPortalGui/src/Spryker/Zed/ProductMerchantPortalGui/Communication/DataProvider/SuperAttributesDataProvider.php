<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;

class SuperAttributesDataProvider implements SuperAttributesDataProviderInterface
{
    protected const FIELD_TITLE = 'title';
    protected const FIELD_VALUE = 'value';
    protected const VALUES = 'values';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct(ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @return string[][]
     */
    public function getSuperAttributes(): array
    {
        $productManagementAttributeTransfers = $this->productAttributeFacade
            ->getProductAttributeCollection();

        $superProductManagementAttributes = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            if ($productManagementAttributeTransfer->getIsSuper()) {
                $values = [];
                foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
                    $values[] = [
                        static::FIELD_TITLE => $productManagementAttributeValueTransfer->getValueOrFail(),
                        static::FIELD_VALUE => $productManagementAttributeValueTransfer->getValueOrFail(),
                    ];
                }

                $superProductManagementAttributes[] = [
                    static::FIELD_TITLE => $productManagementAttributeTransfer->getKeyOrFail(),
                    static::FIELD_VALUE => $productManagementAttributeTransfer->getKeyOrFail(),
                    static::VALUES => $values,
                ];
            }
        }

        return $superProductManagementAttributes;
    }
}
