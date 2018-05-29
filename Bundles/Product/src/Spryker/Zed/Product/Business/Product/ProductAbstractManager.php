<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface;
use Spryker\Zed\Product\Business\Product\Observer\AbstractProductAbstractManagerSubject;
use Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface;
use Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface;
use Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriterInterface;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractManager extends AbstractProductAbstractManagerSubject implements ProductAbstractManagerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface
     */
    protected $productAbstractAssertion;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected $skuGenerator;

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    /**
     * @var \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    protected $productTransferMapper;

    /**
     * @var \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface
     */
    protected $productAbstractStoreRelationReader;

    /**
     * @var \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriterInterface
     */
    protected $productAbstractStoreRelationWriter;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface $productAbstractAssertion
     * @param \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface $skuGenerator
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface $attributeEncoder
     * @param \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface $productTransferMapper
     * @param \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface $productAbstractStoreRelationReader
     * @param \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriterInterface $productAbstractStoreRelationWriter
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductAbstractAssertionInterface $productAbstractAssertion,
        SkuGeneratorInterface $skuGenerator,
        AttributeEncoderInterface $attributeEncoder,
        ProductTransferMapperInterface $productTransferMapper,
        ProductAbstractStoreRelationReaderInterface $productAbstractStoreRelationReader,
        ProductAbstractStoreRelationWriterInterface $productAbstractStoreRelationWriter
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->localeFacade = $localeFacade;
        $this->productAbstractAssertion = $productAbstractAssertion;
        $this->skuGenerator = $skuGenerator;
        $this->attributeEncoder = $attributeEncoder;
        $this->productTransferMapper = $productTransferMapper;
        $this->productAbstractStoreRelationReader = $productAbstractStoreRelationReader;
        $this->productAbstractStoreRelationWriter = $productAbstractStoreRelationWriter;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->productQueryContainer
            ->queryProductAbstractBySku($sku)
            ->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productAbstractTransfer->setSku(
            $this->skuGenerator->generateProductAbstractSku($productAbstractTransfer)
        );

        $this->productAbstractAssertion->assertSkuIsUnique($productAbstractTransfer->getSku());

        $productAbstractTransfer = $this->notifyBeforeCreateObservers($productAbstractTransfer);

        $productAbstractEntity = $this->persistEntity($productAbstractTransfer);

        $idProductAbstract = $productAbstractEntity->getPrimaryKey();
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->persistProductAbstractLocalizedAttributes($productAbstractTransfer);
        $this->persistProductAbstractStoreRelation($productAbstractTransfer, $idProductAbstract);

        $this->notifyAfterCreateObservers($productAbstractTransfer);

        $this->productQueryContainer->getConnection()->commit();

        return $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $idProductAbstract = (int)$productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        $this->productAbstractAssertion->assertProductExists($idProductAbstract);
        $this->productAbstractAssertion->assertSkuIsUniqueWhenUpdatingProduct($idProductAbstract, $productAbstractTransfer->getSku());

        $productAbstractTransfer = $this->notifyBeforeUpdateObservers($productAbstractTransfer);

        $this->persistEntity($productAbstractTransfer);
        $this->persistProductAbstractLocalizedAttributes($productAbstractTransfer);
        $this->persistProductAbstractStoreRelation($productAbstractTransfer, $idProductAbstract);

        $this->notifyAfterUpdateObservers($productAbstractTransfer);

        $this->productQueryContainer->getConnection()->commit();

        return $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function persistProductAbstractStoreRelation(ProductAbstractTransfer $productAbstractTransfer, $idProductAbstract)
    {
        if ($productAbstractTransfer->getStoreRelation() === null) {
            $productAbstractTransfer->setStoreRelation(new StoreRelationTransfer());
        }

        $productAbstractTransfer->getStoreRelation()->setIdEntity($idProductAbstract);
        $this->productAbstractStoreRelationWriter->save($productAbstractTransfer->getStoreRelation());
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku)
    {
        $productAbstract = $this->productQueryContainer
            ->queryProductAbstractBySku($sku)
            ->findOne();

        if (!$productAbstract) {
            return null;
        }

        return $productAbstract->getIdProductAbstract();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract)
    {
        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();

        if (!$productAbstractEntity) {
            return null;
        }

        $productAbstractTransfer = $this->productTransferMapper->convertProductAbstract($productAbstractEntity);
        $productAbstractTransfer = $this->loadLocalizedAttributes($productAbstractTransfer);
        $productAbstractTransfer->setStoreRelation(
            $this->getStoreRelation($idProductAbstract)
        );

        $productAbstractTransfer = $this->notifyReadObservers($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param string $sku
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractBySku(string $sku, int $limit): array
    {
        $productAbstractEntities = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterBySku_Like($sku)
            ->limit($limit)
            ->find();

        if (count($productAbstractEntities) === 0) {
            return [];
        }

        $abstractProducts = [];

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $productAbstractEntity */
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $abstractProducts[] = [
                ProductConstants::PRODUCT_FILTER_ABSTRACT_ID_KEY => $productAbstractEntity->getIdProduct(),
                ProductConstants::PRODUCT_FILTER_RESULT_KEY => $productAbstractEntity->getSku(),
            ];
        }

        return $abstractProducts;
    }

    /**
     * @param string $localizedName
     * @param int $limit
     *
     * @return array
     */
    public function filterProductAbstractByLocalizedName(string $localizedName, int $limit): array
    {
        $locale = $this->localeFacade
            ->getCurrentLocale();

        $locale->requireIdLocale();

        $productAbstractEntities = $this->productQueryContainer
            ->queryProductAbstractWithName(
                $locale->getIdLocale()
            )
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByName_Like($localizedName)
                ->endUse()
            ->limit($limit)
            ->find();

        if (count($productAbstractEntities) === 0) {
            return [];
        }

        $abstractProducts = [];

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity */
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $abstractProducts[] = [
                ProductConstants::PRODUCT_FILTER_ABSTRACT_ID_KEY => $productAbstractEntity->getIdProductAbstract(),
                ProductConstants::PRODUCT_FILTER_RESULT_KEY => $productAbstractEntity
                    ->getVirtualColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME),
            ];
        }

        return $abstractProducts;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getStoreRelation($idProductAbstract)
    {
        return $this->productAbstractStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idProductAbstract)
        );
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku)
    {
        $productConcrete = $this->productQueryContainer
            ->queryProductConcreteBySku($sku)
            ->findOne();

        if (!$productConcrete) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                    $sku
                )
            );
        }

        return $productConcrete->getSpyProductAbstract()->getSku();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function persistEntity(ProductAbstractTransfer $productAbstractTransfer)
    {
        $jsonAttributes = $this->attributeEncoder->encodeAttributes(
            $productAbstractTransfer->getAttributes()
        );

        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractData = $productAbstractTransfer->modifiedToArray();
        if (isset($productAbstractData[ProductAbstractTransfer::ATTRIBUTES])) {
            unset($productAbstractData[ProductAbstractTransfer::ATTRIBUTES]);
        }

        $productAbstractEntity->fromArray($productAbstractData);
        $productAbstractEntity->setAttributes($jsonAttributes);

        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function loadLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAttributeCollection = $this->productQueryContainer
            ->queryProductAbstractLocalizedAttributes($productAbstractTransfer->getIdProductAbstract())
            ->find();

        foreach ($productAttributeCollection as $attributeEntity) {
            $localeTransfer = $this->localeFacade->getLocaleById($attributeEntity->getFkLocale());

            $localizedAttributesData = $attributeEntity->toArray();
            if (isset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES])) {
                unset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES]);
            }

            $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
                ->fromArray($localizedAttributesData, true)
                ->setAttributes($this->attributeEncoder->decodeAttributes($attributeEntity->getAttributes()))
                ->setLocale($localeTransfer);

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function persistProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $idProductAbstract = $productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        $this->productQueryContainer->getConnection()->beginTransaction();

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $jsonAttributes = $this->attributeEncoder->encodeAttributes($localizedAttributes->getAttributes());

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
}
