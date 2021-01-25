<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;

class AttributeWriter implements AttributeWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeValueWriterInterface
     */
    protected $attributeValueWriter;

    /**
     * @var \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface $productAttributeQueryContainer
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeValueWriterInterface $attributeValueWriter
     * @param \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductAttributeQueryContainerInterface $productAttributeQueryContainer,
        ProductAttributeToProductInterface $productFacade,
        ProductAttributeToGlossaryInterface $glossaryFacade,
        AttributeValueWriterInterface $attributeValueWriter,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->productAttributeQueryContainer = $productAttributeQueryContainer;
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

        return $this->getTransactionHandler()->handleTransaction(function () use ($productManagementAttributeTransfer): ProductManagementAttributeTransfer {
            return $this->executeCreateProductManagementAttributeTransaction($productManagementAttributeTransfer);
        });
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

        return $this->getTransactionHandler()->handleTransaction(function () use ($productManagementAttributeTransfer): ProductManagementAttributeTransfer {
            return $this->executeUpdateProductManagementAttributeTransaction($productManagementAttributeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function executeCreateProductManagementAttributeTransaction(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer {
        $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productManagementAttributeTransfer);
        $productManagementAttributeTransfer = $this->createProductManagementAttributeEntity($productManagementAttributeTransfer, $productAttributeKeyTransfer);
        $this->saveGlossaryKeyIfNotExists($productAttributeKeyTransfer);
        $productManagementAttributeTransfer = $this->attributeValueWriter->saveProductAttributeValues($productManagementAttributeTransfer);

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function executeUpdateProductManagementAttributeTransaction(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer {
        $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productManagementAttributeTransfer);
        $productManagementAttributeTransfer = $this->updateProductManagementAttributeEntity($productManagementAttributeTransfer, $productAttributeKeyTransfer);
        $this->saveGlossaryKeyIfNotExists($productAttributeKeyTransfer);
        $productManagementAttributeTransfer = $this->attributeValueWriter->saveProductAttributeValues($productManagementAttributeTransfer);

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
    protected function createProductManagementAttributeEntity(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        ProductAttributeKeyTransfer $productAttributeKeyTransfer
    ) {
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
    protected function updateProductManagementAttributeEntity(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        ProductAttributeKeyTransfer $productAttributeKeyTransfer
    ) {
        $productManagementAttributeEntity = $this->productAttributeQueryContainer
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
