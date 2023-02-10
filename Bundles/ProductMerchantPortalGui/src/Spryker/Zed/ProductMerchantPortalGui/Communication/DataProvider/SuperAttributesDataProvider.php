<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;

class SuperAttributesDataProvider implements SuperAttributesDataProviderInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_VALUE = 'value';

    /**
     * @var string
     */
    protected const FIELD_ATTRIBUTES = 'attributes';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct(ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getSuperAttributes(): array
    {
        $productManagementAttributeTransfers = $this->productAttributeFacade
            ->getProductAttributeCollection();

        $superProductManagementAttributes = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            if (!$productManagementAttributeTransfer->getIsSuperOrFail()) {
                continue;
            }

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

        return $superProductManagementAttributes;
    }
}
