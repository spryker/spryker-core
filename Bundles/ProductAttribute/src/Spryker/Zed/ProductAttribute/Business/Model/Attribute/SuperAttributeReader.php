<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface;

class SuperAttributeReader implements SuperAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface
     */
    protected $productAttributeRepository;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface $productAttributeRepository
     */
    public function __construct(ProductAttributeRepositoryInterface $productAttributeRepository)
    {
        $this->productAttributeRepository = $productAttributeRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getUniqueSuperAttributesFromConcreteProducts(array $productConcreteTransfers): array
    {
        $uniqueTransformedAttributes = $this->getUniqueTransformedAttributes($productConcreteTransfers);
        $superAttributes = $this->productAttributeRepository->findSuperAttributesFromAttributesList($uniqueTransformedAttributes);

        return $superAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return array
     */
    protected function getUniqueTransformedAttributes(array $productConcreteTransfers): array
    {
        $uniqueTransformedAttributes = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            foreach ($productConcreteTransfer->getAttributes() as $attributeKey => $attributeValue) {
                if (!in_array($attributeKey, $uniqueTransformedAttributes)) {
                    $uniqueTransformedAttributes[] = $attributeKey;
                }
            }
        }

        return $uniqueTransformedAttributes;
    }
}
