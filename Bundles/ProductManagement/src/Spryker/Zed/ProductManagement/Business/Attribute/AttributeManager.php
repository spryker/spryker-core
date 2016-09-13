<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Shared\Library\Json;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGeneratorInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGeneratorInterface
     */
    protected $transferGenerator;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferGeneratorInterface $transferGenerator
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductAttributeTransferGeneratorInterface $transferGenerator
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->transferGenerator = $transferGenerator;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->innerJoinSpyProductAttributeKey()
            ->find();

        return $this->transferGenerator->convertProductAttributeCollection($collection);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function getProductAttributeValueCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValue()
            ->find();

        return $this->transferGenerator->convertProductAttributeValueCollection($collection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function persistProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $idProductAbstract = $productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $jsonAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $localizedProductAttributesEntity = $this->productQueryContainer
                ->queryProductAbstractAttributeCollection($idProductAbstract, $locale->getIdLocale())
                ->findOneOrCreate();

            $localizedProductAttributesEntity
                ->setFkProductAbstract($idProductAbstract)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setAttributes($jsonAttributes)
                ->setDescription($localizedAttributes->getDescription())
                ->setMetaTitle($localizedAttributes->getMetaTitle())
                ->setMetaKeywords($localizedAttributes->getMetaKeywords())
                ->setMetaDescription($localizedAttributes->getMetaDescription());

            $localizedProductAttributesEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function persistProductConcreteLocalizedAttributes(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $jsonAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $localizedProductAttributesEntity = $this->productQueryContainer
                ->queryProductConcreteAttributeCollection($idProductConcrete, $locale->getIdLocale())
                ->findOneOrCreate();

            $localizedProductAttributesEntity
                ->setFkProduct($idProductConcrete)
                ->setFkLocale($locale->requireIdLocale()->getIdLocale())
                ->setName($localizedAttributes->requireName()->getName())
                ->setAttributes($jsonAttributes)
                ->setDescription($localizedAttributes->getDescription());

            $localizedProductAttributesEntity->save();
        }
    }

    /**
     * @param array $data
     * @param string $attributeJson
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    public function createLocalizedAttributesTransfer(array $data, $attributeJson, LocaleTransfer $localeTransfer)
    {
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->fromArray($data, true)
            ->setAttributes(
                $this->decodeAttributes($attributeJson)
            )
            ->setLocale($localeTransfer);

        return $localizedAttributesTransfer;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeAttributes(array $attributes)
    {
        return Json::encode($attributes);
    }

    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json)
    {
        return Json::decode($json, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer[] $concreteProductCollection
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function buildAttributeProcessor(ProductAbstractTransfer $productAbstractTransfer, array $concreteProductCollection = [])
    {
        $attributeProcessor = new AttributeProcessor();
        $abstractLocalizedAttributes = [];

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            /* @var \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttribute */
            $localeCode = $localizedAttribute->getLocale()->getLocaleName();
            if (!array_key_exists($localeCode, $abstractLocalizedAttributes)) {
                $abstractLocalizedAttributes[$localeCode] = $localizedAttribute->getAttributes();
            } else {
                $abstractLocalizedAttributes[$localeCode] = array_merge(
                    $abstractLocalizedAttributes[$localeCode],
                    $localizedAttribute->getAttributes()
                );
            }
        }

        $localizedAttributes = [];
        foreach ($concreteProductCollection as $productTransfer) {
            $attributeProcessor->setConcreteAttributes(
                $productTransfer->getAttributes()
            );

            foreach ($productTransfer->getLocalizedAttributes() as $localizedAttribute) {
                /* @var \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttribute */
                $localeCode = $localizedAttribute->getLocale()->getLocaleName();
                if (!array_key_exists($localeCode, $localizedAttributes)) {
                    $localizedAttributes[$localeCode] = $localizedAttribute->getAttributes();
                } else {
                    $localizedAttributes[$localeCode] = array_merge(
                        $localizedAttributes[$localeCode],
                        $localizedAttribute->getAttributes()
                    );
                }
            }
        }

        $attributeProcessor->setConcreteLocalizedAttributes($localizedAttributes);

        $attributeProcessor->setAbstractAttributes(
            $productAbstractTransfer->getAttributes()
        );

        $attributeProcessor->setAbstractLocalizedAttributes(
            $abstractLocalizedAttributes
        );

        return $attributeProcessor;
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    protected function hasProductAbstractAttributes($idProductAbstract, LocaleTransfer $locale)
    {
        $query = $this->productQueryContainer->queryProductAbstractAttributeCollection(
            $idProductAbstract,
            $locale->getIdLocale()
        );

        return $query->count() > 0;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    protected function hasProductConcreteAttributes($idProductConcrete, LocaleTransfer $locale)
    {
        $query = $this->productQueryContainer->queryProductConcreteAttributeCollection(
            $idProductConcrete,
            $locale->getIdLocale()
        );

        return $query->count() > 0;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException
     *
     * @return void
     */
    protected function assertProductConcreteAttributesDoNotExist($idProductConcrete, LocaleTransfer $locale)
    {
        if ($this->hasProductConcreteAttributes($idProductConcrete, $locale)) {
            throw new ProductConcreteAttributesExistException(sprintf(
                'Tried to create product concrete attributes for product id %s, locale id %s, but they exist',
                $idProductConcrete,
                $locale->getIdLocale()
            ));
        }
    }

}
