<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generator;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface;
use Spryker\Zed\Product\Business\Product\Observer\AbstractProductConcreteManagerSubject;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteManager extends AbstractProductConcreteManagerSubject implements ProductConcreteManagerInterface
{
    use TransactionTrait;

    /**
     * @var array<\Generated\Shared\Transfer\LocaleTransfer>
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
     * @var array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface>
     */
    protected $productConcreteExpanderPlugins;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface
     */
    protected $productEventTrigger;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface $productAbstractAssertion
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface $productConcreteAssertion
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface $attributeEncoder
     * @param \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface $productTransferMapper
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface> $productConcreteExpanderPlugins
     * @param \Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface $productEventTrigger
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductAbstractAssertionInterface $productAbstractAssertion,
        ProductConcreteAssertionInterface $productConcreteAssertion,
        AttributeEncoderInterface $attributeEncoder,
        ProductTransferMapperInterface $productTransferMapper,
        ProductRepositoryInterface $productRepository,
        array $productConcreteExpanderPlugins,
        ProductEventTriggerInterface $productEventTrigger
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->localeFacade = $localeFacade;
        $this->productAbstractAssertion = $productAbstractAssertion;
        $this->productConcreteAssertion = $productConcreteAssertion;
        $this->attributeEncoder = $attributeEncoder;
        $this->productTransferMapper = $productTransferMapper;
        $this->productRepository = $productRepository;
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
        $this->productEventTrigger = $productEventTrigger;
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
        return $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfer): int {
            return $this->executeCreateProductConcrete($productConcreteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfer): int {
            return $this->executeUpdateProductConcreteTransaction($productConcreteTransfer);
        });
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
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcreteByIds(array $productIds): array
    {
        $productEntityTransfers = $this->productRepository->findProductConcreteByIds($productIds);

        return $this->loadProductTransfers($productEntityTransfers);
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
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcretesBySkus(array $skus): array
    {
        $productConcreteEntities = $this->productQueryContainer
            ->queryProduct()
            ->filterBySku_In($skus)
            ->joinWithSpyProductAbstract()
            ->find();

        if (!$productConcreteEntities->getData()) {
            return [];
        }

        $productConcreteTransfers = $this->productTransferMapper->convertProductCollection($productConcreteEntities);

        return $productConcreteTransfers;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku)
    {
        $productConcreteTransfer = $this->findProductConcreteBySku($concreteSku);

        $this->assertProductConcreteTransfer($concreteSku, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @deprecated Use
     *     {@link \Spryker\Zed\Product\Business\Product\ProductConcreteManager::getProductConcretesByConcreteSkus()}
     *     instead.
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
                    $productConcreteSku,
                ),
            );
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract): array
    {
        $entityCollection = $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->joinSpyProductAbstract()
            ->find();

        $productTransfers = $this->productTransferMapper->convertProductCollection($entityCollection);

        return $this->loadProductData($productTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function loadProductData(array $productTransfers): array
    {
        $productTransfers = $this->loadLocalizedAttributes($productTransfers);

        foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
            $productConcreteExpanderPlugin->expand($productTransfers);
        }

        $productTransfers = $this->triggerProductReadEvents($productTransfers);

        return $productTransfers;
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
                    $sku,
                ),
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
     * @return array<int>
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        return $this->productRepository->findProductConcreteIdsByAbstractProductId($idProductAbstract);
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
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
                    $idProductConcrete,
                ),
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
     * @return int
     */
    protected function executeCreateProductConcrete(ProductConcreteTransfer $productConcreteTransfer): int
    {
        $sku = $productConcreteTransfer->getSku();
        $this->productConcreteAssertion->assertSkuIsUnique($sku);

        $productConcreteTransfer = $this->notifyBeforeCreateObservers($productConcreteTransfer);

        $productConcreteEntity = $this->persistEntity($productConcreteTransfer);

        $idProductConcrete = $productConcreteEntity->getPrimaryKey();
        $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->persistProductConcreteLocalizedAttributes($productConcreteTransfer);

        $this->notifyAfterCreateObservers($productConcreteTransfer);

        return $idProductConcrete;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    protected function executeUpdateProductConcreteTransaction(ProductConcreteTransfer $productConcreteTransfer): int
    {
        $sku = $productConcreteTransfer
            ->requireSku()
            ->getSku();

        $idProduct = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        $idProductAbstract = $productConcreteTransfer->getFkProductAbstractOrFail();

        $this->productAbstractAssertion->assertProductExists($idProductAbstract);
        $this->productConcreteAssertion->assertProductExists($idProduct);
        $this->productConcreteAssertion->assertSkuIsUniqueWhenUpdatingProduct($idProduct, $sku);

        $productConcreteTransfer = $this->notifyBeforeUpdateObservers($productConcreteTransfer);

        $productConcreteEntity = $this->persistEntity($productConcreteTransfer);

        $idProductConcrete = $productConcreteEntity->getPrimaryKey();
        $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->persistProductConcreteLocalizedAttributes($productConcreteTransfer);

        $this->notifyAfterUpdateObservers($productConcreteTransfer);

        $this->productEventTrigger->triggerProductUpdateEvents([$idProductAbstract]);

        return $idProductConcrete;
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
            $productConcreteTransfer->getAttributes(),
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

        return $this->loadProductData([$productTransfer])[0];
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyProductEntityTransfer> $productEntityTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function loadProductTransfers(array $productEntityTransfers): array
    {
        $productConcreteTransfers = [];

        foreach ($productEntityTransfers as $productEntityTransfer) {
            $productConcreteTransfers[] = $this->productTransferMapper->mapSpyProductEntityTransferToProductConcreteTransfer($productEntityTransfer);
        }

        return $this->loadProductData($productConcreteTransfers);
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
    protected function loadRawProductData(ProductConcreteTransfer $productTransfer): ProductConcreteTransfer
    {
        $productTransfer = $this->loadLocalizedAttributes([$productTransfer])[0];
        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_READ, $productTransfer);

        return $productTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function loadLocalizedAttributes(array $productTransfers): array
    {
        $productIds = [];

        foreach ($productTransfers as $productTransfer) {
            $productIds[] = $productTransfer->getIdProductConcreteOrFail();
        }

        $localizedAttributesGroupedByIdProduct = $this->productRepository->getLocalizedAttributesGroupedByIdProduct($productIds);

        foreach ($productTransfers as $productTransfer) {
            $productTransfer->setLocalizedAttributes(
                new ArrayObject($localizedAttributesGroupedByIdProduct[$productTransfer->getIdProductConcreteOrFail()] ?? []),
            );
        }

        return $productTransfers;
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
        $productConcreteTransfer->requireIdProductConcrete();

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfer): void {
            $this->executePersistProductConcreteLocalizedAttributesTransaction($productConcreteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function executePersistProductConcreteLocalizedAttributesTransaction(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $idProductConcrete = $productConcreteTransfer->getIdProductConcrete();

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $jsonAttributes = $localizedAttributes->getAttributes();
            if (is_array($jsonAttributes)) {
                $jsonAttributes = $this->attributeEncoder->encodeAttributes($localizedAttributes->getAttributes());
            }

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
     * @param array<string> $skus
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array
    {
        return $this->productRepository->getProductConcreteIdsByConcreteSkus($skus);
    }

    /**
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductConcreteSkusByConcreteIds(array $productIds): array
    {
        return $this->productRepository->getProductConcreteSkusByConcreteIds($productIds);
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByConcreteSkus(array $productConcreteSkus): array
    {
        return $this->productRepository->getProductConcretesByConcreteSkus($productConcreteSkus);
    }

    /**
     * @param int $chunkSize
     * @param int|null $idStore Deprecated: Will be removed without replacement.
     *
     * @return \Generator
     */
    public function getAllProductConcreteIdsByChunks(int $chunkSize, ?int $idStore = null): Generator
    {
        $lastProductId = 0;

        while (true) {
            $productIds = $this->productRepository->getAllProductConcreteIdsWithLimit(
                $chunkSize,
                $lastProductId,
                $idStore,
            );

            if (!$productIds) {
                break;
            }

            $lastProductId = end($productIds);

            yield $productIds;
        }
    }

    /**
     * @deprecated Will be removed without replacement. Exists only for BS reasons.
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function triggerProductReadEvents(array $productTransfers): array
    {
        foreach ($productTransfers as $key => $productTransfer) {
            $productTransfers[$key] = $this->notifyReadObservers($productTransfer);
        }

        return $productTransfers;
    }
}
