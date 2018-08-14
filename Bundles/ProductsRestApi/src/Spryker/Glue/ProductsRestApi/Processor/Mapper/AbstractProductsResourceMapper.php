<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class AbstractProductsResourceMapper implements AbstractProductsResourceMapperInterface
{
    protected const KEY_PRODUCT_CONCRETE_IDS = 'product_concrete_ids';
    protected const KEY_ATTRIBUTE_VARIANTS = 'attribute_variants';
    protected const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param array $abstractProductData
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAbstractProductsResponseAttributesTransferToRestResponse(array $abstractProductData): RestResourceInterface
    {
        $restAbstractProductsAttributesTransfer = (new AbstractProductsRestAttributesTransfer())
            ->fromArray($abstractProductData, true);
        $this->changeIdsToSkus($restAbstractProductsAttributesTransfer);

        return $this->restResourceBuilder->createRestResource(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $restAbstractProductsAttributesTransfer->getSku(),
            $restAbstractProductsAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $restAbstractProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    protected function changeIdsToSkus(
        AbstractProductsRestAttributesTransfer $restAbstractProductsAttributesTransfer
    ): AbstractProductsRestAttributesTransfer {

        $attributeMap = $restAbstractProductsAttributesTransfer->getAttributeMap();
        if (!isset($attributeMap[static::KEY_PRODUCT_CONCRETE_IDS])) {
            return $restAbstractProductsAttributesTransfer;
        }
        $productConcreteIds = array_flip($attributeMap[static::KEY_PRODUCT_CONCRETE_IDS]);

        if (isset($attributeMap[static::KEY_ATTRIBUTE_VARIANTS])) {
            foreach ($attributeMap[static::KEY_ATTRIBUTE_VARIANTS] as $key => $data) {
                $attributeMap[static::KEY_ATTRIBUTE_VARIANTS][$key][static::KEY_ID_PRODUCT_CONCRETE] =
                    $productConcreteIds[$data[static::KEY_ID_PRODUCT_CONCRETE]];
            }
        }

        $attributeMap[static::KEY_PRODUCT_CONCRETE_IDS] = array_values($productConcreteIds);

        return $restAbstractProductsAttributesTransfer
            ->setAttributeMap($attributeMap);
    }
}
