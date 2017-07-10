<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeWriter implements AttributeWriterInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueWriterInterface
     */
    protected $attributeValueWriter;

    /**
     * @var \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueWriterInterface $attributeValueWriter
     * @param \Spryker\Shared\ProductManagement\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToGlossaryInterface $glossaryFacade,
        AttributeValueWriterInterface $attributeValueWriter,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->productFacade = $productFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->attributeValueWriter = $attributeValueWriter;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $this->assertProductManagementAttributeTransferRequirements($productManagementAttributeTransfer);

        $this->productManagementQueryContainer->getConnection()->beginTransaction();

        $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productManagementAttributeTransfer);
        $productManagementAttributeTransfer = $this->createProductManagementAttributeEntity($productManagementAttributeTransfer, $productAttributeKeyTransfer);
        $this->saveGlossaryKeyIfNotExists($productAttributeKeyTransfer);
        $productManagementAttributeTransfer = $this->attributeValueWriter->saveProductAttributeValues($productManagementAttributeTransfer);

        $this->productManagementQueryContainer->getConnection()->commit();

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function updateProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $this->assertProductManagementAttributeTransferHasId($productManagementAttributeTransfer);
        $this->assertProductManagementAttributeTransferRequirements($productManagementAttributeTransfer);

        $this->productManagementQueryContainer->getConnection()->beginTransaction();

        $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productManagementAttributeTransfer);
        $productManagementAttributeTransfer = $this->updateProductManagementAttributeEntity($productManagementAttributeTransfer, $productAttributeKeyTransfer);
        $this->saveGlossaryKeyIfNotExists($productAttributeKeyTransfer);
        $productManagementAttributeTransfer = $this->attributeValueWriter->saveProductAttributeValues($productManagementAttributeTransfer);

        $this->productManagementQueryContainer->getConnection()->commit();

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    protected function findOrCreateProductAttributeKey(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        if ($this->productFacade->hasProductAttributeKey($productManagementAttributeTransfer->getKey())) {
            $productAttributeKeyTransfer = $this->productFacade->findProductAttributeKey($productManagementAttributeTransfer->getKey());

            return $productAttributeKeyTransfer;
        }

        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer->fromArray($productManagementAttributeTransfer->toArray(), true);
        $productAttributeKeyTransfer = $this->productFacade->createProductAttributeKey($productAttributeKeyTransfer);

        return $productAttributeKeyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function createProductManagementAttributeEntity(ProductManagementAttributeTransfer $productManagementAttributeTransfer, ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        $productManagementAttributeEntity = new SpyProductManagementAttribute();
        $productManagementAttributeEntity->fromArray($productManagementAttributeTransfer->toArray());
        $productManagementAttributeEntity->setFkProductAttributeKey($productAttributeKeyTransfer->getIdProductAttributeKey());

        $productManagementAttributeEntity->save();
        $productManagementAttributeTransfer->setIdProductManagementAttribute($productManagementAttributeEntity->getIdProductManagementAttribute());

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function updateProductManagementAttributeEntity(ProductManagementAttributeTransfer $productManagementAttributeTransfer, ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        $productManagementAttributeEntity = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->findOneByIdProductManagementAttribute($productManagementAttributeTransfer->getIdProductManagementAttribute());

        $productManagementAttributeEntity->fromArray($productManagementAttributeTransfer->modifiedToArray());
        $productManagementAttributeEntity->setFkProductAttributeKey($productAttributeKeyTransfer->getIdProductAttributeKey());

        $productManagementAttributeEntity->save();

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return void
     */
    protected function saveGlossaryKeyIfNotExists(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        $glossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($productAttributeKeyTransfer->getKey());
        if ($this->glossaryFacade->hasKey($glossaryKey) === false) {
            $this->glossaryFacade->createKey($glossaryKey);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return void
     */
    protected function assertProductManagementAttributeTransferHasId(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $productManagementAttributeTransfer
            ->requireIdProductManagementAttribute();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return void
     */
    protected function assertProductManagementAttributeTransferRequirements(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $productManagementAttributeTransfer
            ->requireInputType()
            ->requireKey();
    }

}
