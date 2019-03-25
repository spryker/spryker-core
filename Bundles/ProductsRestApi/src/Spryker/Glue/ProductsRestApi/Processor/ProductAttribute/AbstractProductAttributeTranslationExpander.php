<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\ProductAttribute;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToGlossaryStorageClientInterface;

class AbstractProductAttributeTranslationExpander implements AbstractProductAttributeTranslationExpanderInterface
{
    protected const GLOSSARY_PRODUCT_ATTRIBUTE_NAME_KEY_PREFIX = 'product.attribute.';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Dependency\Client\ProductsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ProductsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer
     */
    public function addProductAttributeTranslation(
        AbstractProductsRestAttributesTransfer $abstractProductsRestAttributesTransfer,
        string $localeName
    ): AbstractProductsRestAttributesTransfer {
        $attributeNames = [];

        foreach ($abstractProductsRestAttributesTransfer->getAttributes() as $key => $value) {
            $glossaryKey = static::GLOSSARY_PRODUCT_ATTRIBUTE_NAME_KEY_PREFIX . $key;
            $attributeNames[$key] = $this->glossaryStorageClient->translate($glossaryKey, $localeName);
        }
        foreach ($abstractProductsRestAttributesTransfer->getSuperAttributes() as $key => $value) {
            $glossaryKey = static::GLOSSARY_PRODUCT_ATTRIBUTE_NAME_KEY_PREFIX . $key;
            $attributeNames[$key] = $this->glossaryStorageClient->translate($glossaryKey, $localeName);
        }

        return $abstractProductsRestAttributesTransfer->setAttributeNames($attributeNames);
    }
}
