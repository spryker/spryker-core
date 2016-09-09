<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeManagerInterface;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductTransferGenerator;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToUrlInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class ProductConcreteManager implements ProductConcreteManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Attribute\AttributeManagerInterface
     */
    protected $attributeManager;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\Price\Persistence\PriceQueryContainerInterface
     */
    protected $productPriceContainer;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    protected $stockQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface
     */
    protected $productImageFacade;

    public function __construct(
        AttributeManagerInterface $attributeManager,
        ProductQueryContainerInterface $productQueryContainer,
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToTouchInterface $touchFacade,
        ProductManagementToUrlInterface $urlFacade,
        ProductManagementToLocaleInterface $localeFacade,
        ProductManagementToPriceInterface $priceFacade,
        ProductManagementToStockInterface $stockFacade,
        ProductManagementToProductImageInterface $productImageFacade
    ) {
        $this->attributeManager = $attributeManager;
        $this->productQueryContainer = $productQueryContainer;
        $this->productFacade = $productFacade;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
        $this->localeFacade = $localeFacade;
        $this->priceFacade = $priceFacade;
        $this->stockFacade = $stockFacade;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     * @param int $idProductAbstract
     *
     * @return int
     */
    public function createProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer, $idProductAbstract)
    {
        $sku = $productConcreteTransfer->getSku();
        $this->assertProductConcreteSkuUnique($sku);

        $encodedAttributes = $this->attributeManager->encodeAttributes($productConcreteTransfer->getAttributes());

        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity
            ->setSku($sku)
            ->setFkProductAbstract($idProductAbstract)
            ->setAttributes($encodedAttributes)
            ->setIsActive($productConcreteTransfer->getIsActive());

        $productConcreteEntity->save();

        $idProductConcrete = $productConcreteEntity->getPrimaryKey();
        $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->attributeManager->createProductConcreteLocalizedAttributes($productConcreteTransfer);

        return $idProductConcrete;
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Exception
     *
     * @return int
     */
    public function saveProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        try {
            $sku = $productConcreteTransfer->requireSku()->getSku();
            $idProduct = (int)$productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete();
            $idProductAbstract = (int)$productConcreteTransfer->requireFkProductAbstract()->getFkProductAbstract();

            $productConcreteEntity = $this->productQueryContainer
                ->queryProduct()
                ->filterByIdProduct($idProduct)
                ->findOne();

            if (!$productConcreteEntity) {
                throw new MissingProductException(sprintf(
                    'Tried to retrieve an product concrete with id %s, but it does not exist.',
                    $idProduct
                ));
            }

            $existingSku = $this->productQueryContainer
                ->queryProduct()
                ->filterBySku($sku)
                ->findOne();

            if ($existingSku) {
                if ($idProduct !== (int)$existingSku->getIdProduct()) {
                    throw new ProductAbstractExistsException(sprintf(
                        'Tried to create an product concrete with sku %s that already exists',
                        $sku
                    ));
                }
            }

            $jsonAttributes = $this->attributeManager->encodeAttributes($productConcreteTransfer->getAttributes());

            $productConcreteEntity
                ->setSku($sku)
                ->setFkProductAbstract($idProductAbstract)
                ->setAttributes($jsonAttributes)
                ->setIsActive($productConcreteTransfer->getIsActive());

            $productConcreteEntity->save();

            $idProductConcrete = $productConcreteEntity->getPrimaryKey();
            $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

            $this->attributeManager->saveProductConcreteLocalizedAttributes($productConcreteTransfer);

            $priceTransfer = $productConcreteTransfer->getPrice();
            if ($priceTransfer) {
                $this->priceFacade->persistConcreteProductPrice($priceTransfer);
            }

            $imageSetTransferCollection = $productConcreteTransfer->getImageSets();
            if ($imageSetTransferCollection) {
                foreach ($imageSetTransferCollection as $imageSetTransfer) {
                    $this->productImageFacade->persistProductImageSet($imageSetTransfer);
                }
            }

            /* @var \Generated\Shared\Transfer\StockProductTransfer[] $stockCollection */
            $stockCollection = $productConcreteTransfer->getStock();
            foreach ($stockCollection as $stockTransfer) {
                if (!$this->stockFacade->hasStockProduct($stockTransfer->getSku(), $stockTransfer->getStockType())) {
                    $this->stockFacade->createStockProduct($stockTransfer);
                } else {
                    $this->stockFacade->updateStockProduct($stockTransfer);
                }
            }

            $this->productQueryContainer->getConnection()->commit();

            return $idProductConcrete;

        } catch (Exception $e) {
            $this->productQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return void
     */
    protected function assertProductConcreteSkuUnique($sku)
    {
        if ($this->productFacade->hasProductConcrete($sku)) {
            throw new ProductConcreteExistsException(sprintf(
                'Tried to create a product concrete with sku %s, but it already exists',
                $sku
            ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer
     */
    protected function loadPriceForProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        $priceTransfer = $this->priceFacade->getProductConcretePrice(
            $productConcreteTransfer->getIdProductConcrete()
        );

        if ($priceTransfer === null) {
            return $productConcreteTransfer;
        }

        $productConcreteTransfer->setPrice($priceTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer
     */
    protected function loadStockForProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        $stockCollection = $this->stockQueryContainer
            ->queryStockByProducts($productConcreteTransfer->getIdProductConcrete())
            ->innerJoinStock()
            ->find();

        if ($stockCollection === null) {
            return $productConcreteTransfer;
        }

        foreach ($stockCollection as $stockEntity) {
            $stockTransfer = (new StockProductTransfer())
                ->fromArray($stockEntity->toArray(), true)
                ->setStockType($stockEntity->getStock()->getName());

            $productConcreteTransfer->addStock($stockTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer
     */
    protected function loadImageSetForProductConcrete(ZedProductConcreteTransfer $productTransfer)
    {
        $imageSets = $this->productImageFacade
            ->getProductImagesSetCollectionByProductId($productTransfer->getIdProductConcrete());

        if ($imageSets === null) {
            return $productTransfer;
        }

        $productTransfer->setImageSets(
            new ArrayObject($imageSets)
        );

        return $productTransfer;
    }

    /**
     * @param int $idProduct
     *
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer|null
     */
    public function getProductConcreteById($idProduct)
    {
        $product = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProduct)
            ->findOne();

        if (!$product) {
            return null;
        }

        $transferGenerator = new ProductTransferGenerator();
        $productTransfer = $transferGenerator->convertProduct($product);

        $productTransfer = $this->loadProductConcreteLocalizedAttributes($productTransfer);
        $this->loadPriceForProductConcrete($productTransfer);
        $this->loadStockForProductConcrete($productTransfer);
        $this->loadImageSetForProductConcrete($productTransfer);

        return $productTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer
     */
    protected function loadProductConcreteLocalizedAttributes(ZedProductConcreteTransfer $productTransfer)
    {
        $productAttributeCollection = $this->productQueryContainer
            ->queryProductLocalizedAttributes($productTransfer->getIdProductConcrete())
            ->find();

        foreach ($productAttributeCollection as $attributeEntity) {
            $localeTransfer = $this->localeFacade->getLocaleById($attributeEntity->getFkLocale());

            $localizedAttributesTransfer = $this->attributeManager->createLocalizedAttributesTransfer(
                $attributeEntity->toArray(),
                $attributeEntity->getAttributes(),
                $localeTransfer
            );

            $productTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ZedProductConcreteTransfer[]
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        $entityCollection = $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($idProductAbstract)
            ->joinSpyProductAbstract()
            ->find();

        $transferGenerator = new ProductTransferGenerator(); //TODO inject
        $transferCollection = $transferGenerator->convertProductCollection($entityCollection);

        for ($a = 0; $a < count($transferCollection); $a++) {
            $transferCollection[$a] = $this->loadProductConcreteLocalizedAttributes($transferCollection[$a]);
            $transferCollection[$a] = $this->loadPriceForProductConcrete($transferCollection[$a]);
            $transferCollection[$a] = $this->loadStockForProductConcrete($transferCollection[$a]);
        }

        return $transferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function findProductEntityByAbstract(ProductAbstractTransfer $productAbstractTransfer, ZedProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOne();
    }

}
