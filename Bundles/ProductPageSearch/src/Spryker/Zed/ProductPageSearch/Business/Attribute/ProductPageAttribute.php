<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Attribute;

use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;

class ProductPageAttribute implements ProductPageAttributeInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface $productFacade
     */
    public function __construct(ProductPageSearchToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $abstractAttributesData
     * @param string $abstractLocalizedAttributesData
     * @param string $concreteAttributesData
     * @param string $concreteLocalizedAttributesData
     *
     * @return array
     */
    public function getCombinedProductAttributes(
        $abstractAttributesData,
        $abstractLocalizedAttributesData,
        $concreteAttributesData,
        $concreteLocalizedAttributesData
    ) {
        $decodedAbstractAttributesData = $this->productFacade->decodeProductAttributes($abstractAttributesData);
        $decodedAbstractLocalizedAttributesData = $this->productFacade->decodeProductAttributes($abstractLocalizedAttributesData);

        $decodedConcreteAttributesDataCollection = $this->joinAttributeCollectionValues(
            $this->productFacade->decodeProductAttributes(sprintf('[%s]', $concreteAttributesData))
        );
        $decodedConcreteLocalizedAttributesDataCollection = $this->joinAttributeCollectionValues(
            $this->productFacade->decodeProductAttributes(sprintf('[%s]', $concreteLocalizedAttributesData))
        );

        $rawProductAttributesTransfer = new RawProductAttributesTransfer();
        $rawProductAttributesTransfer
            ->setAbstractAttributes($decodedAbstractAttributesData)
            ->setAbstractLocalizedAttributes($decodedAbstractLocalizedAttributesData)
            ->setConcreteAttributes($decodedConcreteAttributesDataCollection)
            ->setConcreteLocalizedAttributes($decodedConcreteLocalizedAttributesDataCollection);

        return $this->productFacade->combineRawProductAttributes($rawProductAttributesTransfer);
    }

    /**
     * @param array $attributeCollections
     *
     * @return array
     */
    protected function joinAttributeCollectionValues(array $attributeCollections)
    {
        $result = [];

        foreach ($attributeCollections as $attributes) {
            foreach ($attributes as $attributeKey => $attributeValue) {
                $result[$attributeKey][] = $attributeValue;
            }
        }

        $result = array_map(function ($attributeValues) {
            $attributeValues = array_values(array_unique($attributeValues));

            return $attributeValues;
        }, $result);

        return $result;
    }
}
