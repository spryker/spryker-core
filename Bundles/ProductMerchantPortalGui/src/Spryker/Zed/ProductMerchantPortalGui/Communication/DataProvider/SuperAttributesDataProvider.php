<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;

class SuperAttributesDataProvider implements SuperAttributesDataProviderInterface
{
    protected const FIELD_NAME = 'name';
    protected const FIELD_VALUE = 'value';
    protected const FIELD_ATTRIBUTES = 'attributes';

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
     * @return array
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
                        static::FIELD_NAME => $productManagementAttributeValueTransfer->getValueOrFail(),
                        static::FIELD_VALUE => $productManagementAttributeValueTransfer->getValueOrFail(),
                    ];
                }

                $superProductManagementAttributes[] = [
                    static::FIELD_NAME => $productManagementAttributeTransfer->getKeyOrFail(),
                    static::FIELD_VALUE => $productManagementAttributeTransfer->getKeyOrFail(),
                    static::FIELD_ATTRIBUTES => $values,
                ];
            }
        }

        return $superProductManagementAttributes;
    }
}
