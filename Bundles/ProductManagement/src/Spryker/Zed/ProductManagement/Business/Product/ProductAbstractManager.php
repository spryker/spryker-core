<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ZedProductPriceTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
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
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class ProductAbstractManager implements ProductAbstractManagerInterface
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

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

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
        ProductManagementToProductImageInterface $productImageFacade,
        ProductConcreteManagerInterface $productConcreteManager
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
        $this->productConcreteManager = $productConcreteManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Exception
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        try {
            $this->assertProductAbstractSkuIsUnique($productAbstractTransfer->getSku());

            $productAbstractEntity = $this->persistProductAbstractEntity($productAbstractTransfer);

            $idProductAbstract = $productAbstractEntity->getPrimaryKey();
            $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

            $this->attributeManager->createProductAbstractLocalizedAttributes($productAbstractTransfer);
            $this->persistProductAbstractPrice($productAbstractTransfer);
            $this->persistImageSets($productAbstractTransfer);

            $this->productQueryContainer->getConnection()->commit();
            return $idProductAbstract;

        } catch (Exception $e) {
            $this->productQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Exception
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        try {
            $idProductAbstract = (int)$productAbstractTransfer
                ->requireIdProductAbstract()
                ->getIdProductAbstract();

            $this->assertProductAbstractExists($idProductAbstract);
            $this->assertProductAbstractSkuIsUniqueWhenUpdatingProduct($idProductAbstract, $productAbstractTransfer->getSku());

            $this->persistProductAbstractEntity($productAbstractTransfer);

            $this->attributeManager->saveProductAbstractLocalizedAttributes($productAbstractTransfer);
            $this->persistProductAbstractPrice($productAbstractTransfer);
            $this->persistImageSets($productAbstractTransfer);

            $this->productQueryContainer->getConnection()->commit();
            return $idProductAbstract;

        } catch (Exception $e) {
            $this->productQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function persistProductAbstractEntity(ProductAbstractTransfer $productAbstractTransfer)
    {
        $jsonAttributes = $this->attributeManager->encodeAttributes(
            $productAbstractTransfer->getAttributes()
        );

        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractEntity
            ->setAttributes($jsonAttributes)
            ->setSku($productAbstractTransfer->getSku())
            ->setFkTaxSet($productAbstractTransfer->getTaxSetId());

        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function persistProductAbstractPrice(ProductAbstractTransfer $productAbstractTransfer)
    {
        $priceTransfer = $productAbstractTransfer->getPrice();
        if ($priceTransfer instanceof ZedProductPriceTransfer) {
            $priceTransfer->setIdProduct(
                $productAbstractTransfer
                    ->requireIdProductAbstract()
                    ->getIdProductAbstract()
            );
            $this->priceFacade->persistAbstractProductPrice($priceTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function persistImageSets(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetTransferCollection = $productAbstractTransfer->getImageSets();
        if (!empty($imageSetTransferCollection)) {
            foreach ($imageSetTransferCollection as $imageSetTransfer) {
                $imageSetTransfer->setIdProductAbstract(
                    $productAbstractTransfer
                        ->requireIdProductAbstract()
                        ->getIdProductAbstract()
                );
                $this->productImageFacade->persistProductImageSet($imageSetTransfer);
            }
        }
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function getProductAbstractIdBySku($sku)
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
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return void
     */
    protected function assertProductAbstractSkuIsUnique($sku)
    {
        if ($this->productFacade->hasProductAbstract($sku)) {
            throw new ProductAbstractExistsException(sprintf(
                'Product abstract with sku %s already exists',
                $sku
            ));
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return void
     */
    protected function assertProductAbstractSkuIsUniqueWhenUpdatingProduct($idProductAbstract, $sku)
    {
        $isUnique = $this->productQueryContainer
            ->queryProductAbstractBySku($sku)
            ->filterByIdProductAbstract($idProductAbstract, Criteria::NOT_EQUAL)
            ->count() <= 0;

        if (!$isUnique) {
            throw new ProductAbstractExistsException(sprintf(
                'Product abstract with sku %s already exists',
                $sku
            ));
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return void
     */
    protected function assertProductAbstractExists($idProductAbstract)
    {
        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();

        if (!$productAbstractEntity) {
            throw new MissingProductException(sprintf(
                'Product abstract with id "%s" does not exist.',
                $idProductAbstract
            ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function loadTaxRate(ProductAbstractTransfer $productAbstractTransfer)
    {
        $taxSetEntity = $this->productQueryContainer
            ->queryTaxSetForProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->findOne();

        if ($taxSetEntity === null) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setTaxSetId($taxSetEntity->getIdTaxSet());

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function loadImageSetForProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSets = $this->productImageFacade
            ->getProductImagesSetCollectionByProductAbstractId($productAbstractTransfer->getIdProductAbstract());

        if ($imageSets === null) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setImageSets(
            new ArrayObject($imageSets)
        );

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function getProductAbstractById($idProductAbstract)
    {
        $productAbstractEntity = $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();

        if (!$productAbstractEntity) {
            return null;
        }

        $transferGenerator = new ProductTransferGenerator();
        $productAbstractTransfer = $transferGenerator->convertProductAbstract($productAbstractEntity);
        $productAbstractTransfer = $this->loadProductAbstractLocalizedAttributes($productAbstractTransfer);
        $productAbstractTransfer = $this->loadProductAbstractPrice($productAbstractTransfer);
        $productAbstractTransfer = $this->loadTaxRate($productAbstractTransfer);
        $productAbstractTransfer = $this->loadImageSetForProductAbstract($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function loadProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAttributeCollection = $this->productQueryContainer
            ->queryProductAbstractLocalizedAttributes($productAbstractTransfer->getIdProductAbstract())
            ->find();

        foreach ($productAttributeCollection as $attributeEntity) {
            $localeTransfer = $this->localeFacade->getLocaleById($attributeEntity->getFkLocale());

            $localizedAttributesTransfer = $this->attributeManager->createLocalizedAttributesTransfer(
                $attributeEntity->toArray(),
                $attributeEntity->getAttributes(),
                $localeTransfer
            );

            $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function loadProductAbstractPrice(ProductAbstractTransfer $productAbstractTransfer)
    {
        $priceTransfer = $this->priceFacade->getProductAbstractPrice(
            $productAbstractTransfer->getIdProductAbstract()
        );

        if ($priceTransfer === null) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setPrice($priceTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessorInterface
     */
    public function getProductAttributesByAbstractProductId($idProductAbstract)
    {
        $attributeProcessor = new AttributeProcessor();
        $productAbstractTransfer = $this->getProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            return $attributeProcessor;
        }

        $concreteProductCollection = $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);

        return $this->attributeManager->buildAttributeProcessor($productAbstractTransfer, $concreteProductCollection);
    }

}
