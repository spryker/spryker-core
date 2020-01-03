<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface;
use Spryker\Zed\Product\Business\Product\Observer\AbstractProductConcreteManagerSubject;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteManager extends AbstractProductConcreteManagerSubject implements ProductConcreteManagerInterface
{
    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected static $localeTransfersCache;

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
     * @var \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface
     */
    protected $productConcreteAssertion;

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    /**
     * @var \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    protected $productTransferMapper;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface $productAbstractAssertion
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface $productConcreteAssertion
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface $attributeEncoder
     * @param \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface $productTransferMapper
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductAbstractAssertionInterface $productAbstractAssertion,
        ProductConcreteAssertionInterface $productConcreteAssertion,
        AttributeEncoderInterface $attributeEncoder,
        ProductTransferMapperInterface $productTransferMapper,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->localeFacade = $localeFacade;
        $this->productAbstractAssertion = $productAbstractAssertion;
        $this->productConcreteAssertion = $productConcreteAssertion;
        $this->attributeEncoder = $attributeEncoder;
        $this->productTransferMapper = $productTransferMapper;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->productQueryContainer
            ->queryProductConcreteBySku($sku)
            ->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $sku = $productConcreteTransfer->getSku();
        $this->productConcreteAssertion->assertSkuIsUnique($sku);

        $productConcreteTransfer = $this->notifyBeforeCreateObservers($productConcreteTransfer);

        $productConcreteEntity = $this->persistEntity($productConcreteTransfer);

        $idProductConcrete = $productConcreteEntity->getPrimaryKey();
        $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->persistProductConcreteLocalizedAttributes($productConcreteTransfer);

        $this->notifyAfterCreateObservers($productConcreteTransfer);

        $this->productQueryContainer->getConnection()->commit();

        return $idProductConcrete;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $sku = $productConcreteTransfer
            ->requireSku()
            ->getSku();

        $idProduct = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        $idProductAbstract = $productConcreteTransfer
            ->requireFkProductAbstract()
            ->getFkProductAbstract();

        $this->productAbstractAssertion->assertProductExists($idProductAbstract);
        $this->productConcreteAssertion->assertProductExists($idProduct);
        $this->productConcreteAssertion->assertSkuIsUniqueWhenUpdatingProduct($idProduct, $sku);

        $productConcreteTransfer = $this->notifyBeforeUpdateObservers($productConcreteTransfer);

        $productConcreteEntity = $this->persistEntity($productConcreteTransfer);

        $idProductConcrete = $productConcreteEntity->getPrimaryKey();
        $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->persistProductConcreteLocalizedAttributes($productConcreteTransfer);

        $this->notifyAfterUpdateObservers($productConcreteTransfer);

        $this->productQueryContainer->getConnection()->commit();

        return $idProductConcrete;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct)
    {
        $productEntityTransfer = $this->productRepository->findProductConcreteById($idProduct);

        return $this->loadProductTransfer($productEntityTransfer);
    }

    /**
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteBySku(string $productConcreteSku): ?ProductConcreteTransfer
    {
        $productEntityTransfer = $this->productRepository->findProductConcreteBySku($productConcreteSku);

        return $this->loadProductTransfer($productEntityTransfer);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findRawProductConcreteBySku(string $productConcreteSku): ?ProductConcreteTransfer
    {
        $productEntityTransfer = $this->productRepository->findProductConcreteBySku($productConcreteSku);

        return $this->loadRawProductTransfer($productEntityTransfer);
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku)
    {
        $productEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterBySku($sku)
            ->findOne();

        if (!$productEntity) {
            return null;
        }

        return $productEntity->getIdProduct();
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function findProductConcretesBySkus(array $skus): array
    {
        $productConcreteEntities = $this->productQueryContainer
            ->queryProduct()
            ->filterBySku_In($skus)
            ->find();

        if (!$productConcreteEntities->getData()) {
            return [];
        }

        $productConcreteTransfers = $this->productTransferMapper->convertProductCollection($productConcreteEntities);

        return $productConcreteTransfers;
    }

    /**
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($productConcreteSku)
    {
        $productConcreteTransfer = $this->findProductConcreteBySku($productConcreteSku);

        $this->assertProductConcreteTransfer($productConcreteSku, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @deprecated Use `Spryker\Zed\Product\Business\Product\ProductConcreteManager::getProductConcretesByConcreteSkus()` instead.
     *
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getRawProductConcreteBySku(string $productConcreteSku): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->findRawProductConcreteBySku($productConcreteSku);

        $this->assertProductConcreteTransfer($productConcreteSku, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param string $productConcreteSku
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer|null $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return void
     */
    public function assertProductConcreteTransfer(string $productConcreteSku, ?ProductConcreteTransfer $productConcreteTransfer): void
    {
        if (!$productConcreteTransfer) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                    $productConcreteSku
                )
            );
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        $entityCollection = $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->joinSpyProductAbstract()
            ->find();

        $transferCollection = $this->productTransferMapper->convertProductCollection($entityCollection);

        $numberOfProducts = count($transferCollection);
        for ($a = 0; $a < $numberOfProducts; $a++) {
            $transferCollection[$a] = $this->loadProductData($transferCollection[$a]);
        }

        return $transferCollection;
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku)
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

        return $productConcrete->getFkProductAbstract();
    }

    /**
     * @param int $idConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idConcrete): ?int
    {
        return $this->productRepository->findProductAbstractIdByConcreteId($idConcrete);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        return $this->productRepository->findProductConcreteIdsByAbstractProductId($idProductAbstract);
    }

    /**
     * @param int[] $productConcreteIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->productRepository->getProductAbstractIdsByProductConcreteIds($productConcreteIds);
    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteId(int $idProductConcrete): int
    {
        $productConcrete = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->findOne();

        if (!$productConcrete) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a product concrete with id %s, but it does not exist.',
                    $idProductConcrete
                )
            );
        }

        return $productConcrete->getFkProductAbstract();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    public function findProductEntityByAbstractAndConcrete(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function persistEntity(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer
            ->requireSku()
            ->requireFkProductAbstract();

        $encodedAttributes = $this->attributeEncoder->encodeAttributes(
            $productConcreteTransfer->getAttributes()
        );

        $productConcreteEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();

        $productConcreteData = $productConcreteTransfer->modifiedToArray();
        if (isset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES])) {
            unset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES]);
        }

        $productConcreteEntity->fromArray($productConcreteData);
        $productConcreteEntity->setAttributes($encodedAttributes);

        $productConcreteEntity->save();

        return $productConcreteEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer|null $productEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function loadProductTransfer(?SpyProductEntityTransfer $productEntityTransfer): ?ProductConcreteTransfer
    {
        if (!$productEntityTransfer) {
            return null;
        }

        $productTransfer = $this->productTransferMapper->mapSpyProductEntityTransferToProductConcreteTransfer($productEntityTransfer);
        $productTransfer = $this->loadProductData($productTransfer);

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductEntityTransfer|null $productEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function loadRawProductTransfer(?SpyProductEntityTransfer $productEntityTransfer): ?ProductConcreteTransfer
    {
        if (!$productEntityTransfer) {
            return null;
        }

        $productTransfer = $this->productTransferMapper->mapSpyProductEntityTransferToProductConcreteTransfer($productEntityTransfer);
        $productTransfer = $this->loadRawProductData($productTransfer);

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function loadProductData(ProductConcreteTransfer $productTransfer)
    {
        $this->loadLocalizedAttributes($productTransfer);

        $this->notifyReadObservers($productTransfer);

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function loadRawProductData(ProductConcreteTransfer $productTransfer): ProductConcreteTransfer
    {
        $this->loadLocalizedAttributes($productTransfer);
        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_READ, $productTransfer);

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function loadLocalizedAttributes(ProductConcreteTransfer $productTransfer)
    {
        $productAttributeCollection = $this->productQueryContainer
            ->queryProductLocalizedAttributes($productTransfer->getIdProductConcrete())
            ->find();

        foreach ($productAttributeCollection as $attributeEntity) {
            $localeTransfer = $this->getLocaleTransferByIdLocale($attributeEntity->getFkLocale());

            $localizedAttributesData = $attributeEntity->toArray();
            if (isset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES])) {
                unset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES]);
            }

            $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
                ->fromArray($localizedAttributesData, true)
                ->setAttributes($this->attributeEncoder->decodeAttributes($attributeEntity->getAttributes()))
                ->setLocale($localeTransfer);

            $productTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productTransfer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransferByIdLocale(int $idLocale): LocaleTransfer
    {
        if (!isset(static::$localeTransfersCache[$idLocale])) {
            static::$localeTransfersCache[$idLocale] = $this->localeFacade->getLocaleById($idLocale);
        }

        return static::$localeTransfersCache[$idLocale];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function persistProductConcreteLocalizedAttributes(ProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        $this->productQueryContainer->getConnection()->beginTransaction();

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $jsonAttributes = $this->attributeEncoder->encodeAttributes($localizedAttributes->getAttributes());

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
     * @param string[] $skus
     *
     * @return int[]
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array
    {
        return $this->productRepository->getProductConcreteIdsByConcreteSkus($skus);
    }

    /**
     * @param int[] $productIds
     *
     * @return array
     */
    public function getProductConcreteSkusByConcreteIds(array $productIds): array
    {
        return $this->productRepository->getProductConcreteSkusByConcreteIds($productIds);
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcretesByConcreteSkus(array $productConcreteSkus): array
    {
        return $this->productRepository->getProductConcretesByConcreteSkus($productConcreteSkus);
    }
}
