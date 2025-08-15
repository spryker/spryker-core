<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<mixed, array<mixed>|null>
 */
class ProductAttributeTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param mixed $value
     *
     * @return null
     */
    public function transform($value)
    {
        return null;
    }

    /**
     * @param mixed $value
     *
     * @return array<mixed>|null
     */
    public function reverseTransform($value): ?array
    {
        $newAttributes = $this->utilEncodingService->decodeJson($value, true);

        if (!$newAttributes) {
            return null;
        }

        return $newAttributes;
    }
}
