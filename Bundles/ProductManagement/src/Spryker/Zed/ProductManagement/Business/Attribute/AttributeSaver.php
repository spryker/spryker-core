<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductManagement\Persistence\SpyProductManagementAttribute;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeSaver implements AttributeSaverInterface
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
     * @var \Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueSaverInterface
     */
    protected $attributeValueSaver;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\ProductManagement\Business\Attribute\AttributeValueSaverInterface $attributeValueSaver
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToGlossaryInterface $glossaryFacade,
        AttributeValueSaverInterface $attributeValueSaver
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->productFacade = $productFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->attributeValueSaver = $attributeValueSaver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $this->assertProductManagementAttributeTransferRequirements($productManagementAttributeTransfer);

        $this->productManagementQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            // save product management attribute
            $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productManagementAttributeTransfer->getKey());

            $productManagementAttributeEntity = new SpyProductManagementAttribute();
            $productManagementAttributeEntity->fromArray($productManagementAttributeTransfer->toArray());
            $productManagementAttributeEntity->setFkProductAttributeKey($productAttributeKeyTransfer->getIdProductAttributeKey());

            $productManagementAttributeEntity->save();
            $productManagementAttributeTransfer->setIdProductManagementAttribute($productManagementAttributeEntity->getIdProductManagementAttribute());

            // save glossary key for product management attribute key
            $glossaryKey = ProductManagementConstants::PRODUCT_MANAGEMENT_ATTRIBUTE_GLOSSARY_PREFIX . $productAttributeKeyTransfer->getKey();
            if ($this->glossaryFacade->hasKey($glossaryKey) === false) {
                $this->glossaryFacade->createKey($glossaryKey);
            }

            // save attribute values
            $this->attributeValueSaver->saveProductAttributeValues($productManagementAttributeTransfer);

            $this->productManagementQueryContainer
                ->getConnection()
                ->commit();

        } catch (\Exception $e) {
            $this->productManagementQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }

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

        // TODO: need to know if the key is changed or not (load by id instead key would help)
        $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productManagementAttributeTransfer->getKey());

        $productManagementAttributeEntity = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->filterByIdProductManagementAttribute($productManagementAttributeTransfer->getIdProductManagementAttribute())
            ->findOne();

        $productManagementAttributeEntity->fromArray($productManagementAttributeTransfer->modifiedToArray());
        $productManagementAttributeEntity->setFkProductAttributeKey($productAttributeKeyTransfer->getIdProductAttributeKey());

        $productManagementAttributeEntity->save();

        // TODO: update glossary key if attribute key is changed

        // save attribute values
        $this->attributeValueSaver->saveProductAttributeValues($productManagementAttributeTransfer);

        return $productManagementAttributeTransfer;
    }

    /**
     * @param string $productAttributeKey
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    protected function findOrCreateProductAttributeKey($productAttributeKey)
    {
        if ($this->productFacade->hasProductAttributeKey($productAttributeKey)) {
            $productAttributeKeyTransfer = $this->productFacade->getProductAttributeKey($productAttributeKey);

            return $productAttributeKeyTransfer;
        }

        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer->setKey($productAttributeKey);
        $productAttributeKeyTransfer = $this->productFacade->createProductAttributeKey($productAttributeKeyTransfer);

        return $productAttributeKeyTransfer;
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
