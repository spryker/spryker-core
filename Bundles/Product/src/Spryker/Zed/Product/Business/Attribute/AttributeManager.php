<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Library\Json;
use Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AttributeManager implements AttributeManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
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

        $this->productQueryContainer->getConnection()->beginTransaction();

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

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function persistProductConcreteLocalizedAttributes(ProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        $this->productQueryContainer->getConnection()->beginTransaction();

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

        $this->productQueryContainer->getConnection()->commit();
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
        $value = Json::decode($json, true);

        if (!is_array($value)) {
            $value = [];
        }

        return $value;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $concreteProductCollection
     *
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeProcessorInterface
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
     * @param array $localizedAttributeCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $default
     *
     * @return string|null
     */
    public function getProductNameFromLocalizedAttributes(array $localizedAttributeCollection, LocaleTransfer $localeTransfer, $default = null)
    {
        foreach ($localizedAttributeCollection as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttribute->getName();
            }
        }

        return $default;
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
