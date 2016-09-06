<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Product;

use Exception;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\ZedProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Shared\Library\Json;
use Spryker\Zed\ProductManagement\Business\Attribute\AttributeProcessor;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductTransferGenerator;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductImageInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStockInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToTouchInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToUrlInterface;
use Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractAttributesExistException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException;
use Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Stock\Persistence\StockQueryContainerInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface
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
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    protected $productAbstractCollectionBySkuCache = [];

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected $productConcreteCollectionBySkuCache = [];

    /**
     * @var array
     */
    protected $productAbstractsBySkuCache;

    public function __construct(
        AttributeManagerInterface $attributeManager,
        ProductQueryContainerInterface $productQueryContainer,
        StockQueryContainerInterface $stockQueryContainer,
        ProductManagementToTouchInterface $touchFacade,
        ProductManagementToUrlInterface $urlFacade,
        ProductManagementToLocaleInterface $localeFacade,
        ProductManagementToPriceInterface $priceFacade,
        ProductManagementToStockInterface $stockFacade,
        ProductManagementToProductImageInterface $productImageFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
        $this->localeFacade = $localeFacade;
        $this->attributeManager = $attributeManager;
        $this->priceFacade = $priceFacade;
        $this->stockFacade = $stockFacade;
        $this->stockQueryContainer = $stockQueryContainer;
        $this->productImageFacade = $productImageFacade;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        $productAbstractQuery = $this->productQueryContainer->queryProductAbstractBySku($sku);

        return $productAbstractQuery->count() > 0;
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
            $sku = $productAbstractTransfer->getSku();
            $this->checkProductAbstractDoesNotExist($sku);

            $jsonAttributes = $this->encodeAttributes($productAbstractTransfer->getAttributes());

            $productAbstract = new SpyProductAbstract();
            $productAbstract
                ->setAttributes($jsonAttributes)
                ->setSku($sku)
                ->setFkTaxSet($productAbstractTransfer->getTaxSetId());

            $productAbstract->save();

            $idProductAbstract = $productAbstract->getPrimaryKey();
            $productAbstractTransfer->setIdProductAbstract($idProductAbstract);
            $this->createProductAbstractLocalizedAttributes($productAbstractTransfer);

            $priceTransfer = $productAbstractTransfer->getPrice();
            if ($priceTransfer !== null) {
                $priceTransfer->setIdProduct($idProductAbstract);
                $this->priceFacade->persistAbstractProductPrice($priceTransfer);
            }

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
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        try {
            $sku = $productAbstractTransfer->getSku();
            $idProductAbstract = (int)$productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract();

            $productAbstract = $this->productQueryContainer
                ->queryProductAbstract()
                ->filterByIdProductAbstract($idProductAbstract)
                ->findOne();

            if (!$productAbstract) {
                throw new MissingProductException(sprintf(
                    'Tried to retrieve an product abstract with id %s, but it does not exist.',
                    $idProductAbstract
                ));
            }

            $existingAbstractSku = $this->productQueryContainer
                ->queryProductAbstractBySku($sku)
                ->findOne();

            if ($existingAbstractSku) {
                if ($idProductAbstract !== (int)$existingAbstractSku->getIdProductAbstract()) {
                    throw new ProductAbstractExistsException(sprintf(
                        'Tried to create an product abstract with sku %s that already exists',
                        $sku
                    ));
                }
            }

            $jsonAttributes = $this->encodeAttributes($productAbstractTransfer->getAttributes());

            $productAbstract
                ->setAttributes($jsonAttributes)
                ->setSku($sku)
                ->setFkTaxSet($productAbstractTransfer->getTaxSetId());

            $this->priceFacade->persistAbstractProductPrice($productAbstractTransfer->getPrice());

            $productAbstract->save();

            $idProductAbstract = $productAbstract->getPrimaryKey();
            $productAbstractTransfer->setIdProductAbstract($idProductAbstract);
            $this->saveProductAbstractLocalizedAttributes($productAbstractTransfer);

            $priceTransfer = $productAbstractTransfer->getPrice();
            if ($priceTransfer !== null) {
                $priceTransfer->setIdProduct($idProductAbstract);
                $this->priceFacade->persistAbstractProductPrice($priceTransfer);
            }

            $imageSetTransferCollection = $productAbstractTransfer->getImageSets();
            if (!empty($imageSetTransferCollection)) {
                foreach ($imageSetTransferCollection as $imageSetTransfer) {
                    $this->productImageFacade->persistProductImageSet($imageSetTransfer);
                }
            }

            $this->productQueryContainer->getConnection()->commit();

            return $idProductAbstract;
        }
        catch (Exception $e) {
            $this->productQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku)
    {
        if (!isset($this->productAbstractsBySkuCache[$sku])) {
            $productAbstract = $this->productQueryContainer->queryProductAbstractBySku($sku)->findOne();

            if (!$productAbstract) {
                throw new MissingProductException(sprintf(
                    'Tried to retrieve an product abstract with sku %s, but it does not exist.',
                    $sku
                ));
            }

            $this->productAbstractsBySkuCache[$sku] = $productAbstract;
        }

        return $this->productAbstractsBySkuCache[$sku]->getPrimaryKey();
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return void
     */
    protected function checkProductAbstractDoesNotExist($sku)
    {
        if ($this->hasProductAbstract($sku)) {
            throw new ProductAbstractExistsException(sprintf(
                'Tried to create an product abstract with sku %s that already exists',
                $sku
            ));
        }
    }

    /**
     * TODO move to AttributeManager
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractAttributesExistException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function createProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $idProductAbstract = $productAbstractTransfer->getIdProductAbstract();

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            if ($this->hasProductAbstractAttributes($idProductAbstract, $locale)) {
                continue;
            }

            $encodedAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $productAbstractAttributesEntity = new SpyProductAbstractLocalizedAttributes();
            $productAbstractAttributesEntity
                ->setFkProductAbstract($idProductAbstract)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setDescription($localizedAttributes->getDescription())
                ->setMetaTitle($localizedAttributes->getMetaTitle())
                ->setMetaDescription($localizedAttributes->getMetaDescription())
                ->setMetaKeywords($localizedAttributes->getMetaKeywords())
                ->setAttributes($encodedAttributes);

            $productAbstractAttributesEntity->save();
        }
    }

    /**
     * TODO move to AttributeManager
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractAttributesExistException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function saveProductAbstractLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
    {
        $idProductAbstract = $productAbstractTransfer->getIdProductAbstract();

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
     * TODO move to AttributeManager
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @deprecated Use hasProductAbstractAttributes() instead.
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractAttributesExistException
     *
     * @return void
     */
    protected function checkProductAbstractAttributesDoNotExist($idProductAbstract, $locale)
    {
        if ($this->hasProductAbstractAttributes($idProductAbstract, $locale)) {
            throw new ProductAbstractAttributesExistException(sprintf(
                'Tried to create abstract attributes for product abstract %s, locale id %s, but it already exists',
                $idProductAbstract,
                $locale->getIdLocale()
            ));
        }
    }

    /**
     * TODO move to AttributeManager
     *
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
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return int
     */
    public function createProductConcrete(ZedProductConcreteTransfer $productConcreteTransfer, $idProductAbstract)
    {
        $sku = $productConcreteTransfer->getSku();
        $this->checkProductConcreteDoesNotExist($sku);

        $encodedAttributes = $this->encodeAttributes($productConcreteTransfer->getAttributes());

        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity
            ->setSku($sku)
            ->setFkProductAbstract($idProductAbstract)
            ->setAttributes($encodedAttributes)
            ->setIsActive($productConcreteTransfer->getIsActive());

        $productConcreteEntity->save();

        $idProductConcrete = $productConcreteEntity->getPrimaryKey();
        $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->createProductConcreteLocalizedAttributes($productConcreteTransfer);

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

            $jsonAttributes = $this->encodeAttributes($productConcreteTransfer->getAttributes());

            $productConcreteEntity
                ->setSku($sku)
                ->setFkProductAbstract($idProductAbstract)
                ->setAttributes($jsonAttributes)
                ->setIsActive($productConcreteTransfer->getIsActive());

            $productConcreteEntity->save();

            $idProductConcrete = $productConcreteEntity->getPrimaryKey();
            $productConcreteTransfer->setIdProductConcrete($idProductConcrete);

            $this->saveProductConcreteLocalizedAttributes($productConcreteTransfer);

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
    protected function checkProductConcreteDoesNotExist($sku)
    {
        if ($this->hasProductConcrete($sku)) {
            throw new ProductConcreteExistsException(sprintf(
                'Tried to create a product concrete with sku %s, but it already exists',
                $sku
            ));
        }
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->productQueryContainer->queryProductConcreteBySku($sku)->count() > 0;
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku)
    {
        if (!isset($this->productConcreteCollectionBySkuCache[$sku])) {
            $productConcrete = $this->productQueryContainer
                ->queryProductConcreteBySku($sku)
                ->findOne();

            if (!$productConcrete) {
                throw new MissingProductException(sprintf(
                    'Tried to retrieve a product concrete with sku %s, but it does not exist',
                    $sku
                ));
            }

            $this->productConcreteCollectionBySkuCache[$sku] = $productConcrete;
        }

        return $this->productConcreteCollectionBySkuCache[$sku]->getPrimaryKey();
    }

    /**
     * TODO move to AttributeManager
     *
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function createProductConcreteLocalizedAttributes(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer->getIdProductConcrete();

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $this->checkProductConcreteAttributesDoNotExist($idProductConcrete, $locale);

            $jsonAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $productAttributeEntity = new SpyProductLocalizedAttributes();
            $productAttributeEntity
                ->setFkProduct($idProductConcrete)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setAttributes($jsonAttributes);

            $productAttributeEntity->save();
        }
    }

    /**
     * TODO move to AttributeManager
     *
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function saveProductConcreteLocalizedAttributes(ZedProductConcreteTransfer $productConcreteTransfer)
    {
        $idProductConcrete = $productConcreteTransfer->requireIdProductConcrete()->getIdProductConcrete();

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $jsonAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $localizedProductAttributesEntity = $this->productQueryContainer
                ->queryProductConcreteAttributeCollection($idProductConcrete, $locale->getIdLocale())
                ->findOneOrCreate();

            $localizedProductAttributesEntity
                ->setFkProduct($idProductConcrete)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setAttributes($jsonAttributes)
                ->setDescription($localizedAttributes->getDescription());

            $localizedProductAttributesEntity->save();
        }
    }

    /**
     * TODO move to AttributeManager
     *
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteAttributesExistException
     *
     * @return void
     */
    protected function checkProductConcreteAttributesDoNotExist($idProductConcrete, LocaleTransfer $locale)
    {
        if ($this->hasProductConcreteAttributes($idProductConcrete, $locale)) {
            throw new ProductConcreteAttributesExistException(sprintf(
                'Tried to create product concrete attributes for product id %s, locale id %s, but they exist',
                $idProductConcrete,
                $locale->getIdLocale()
            ));
        }
    }

    /**
     * TODO move to AttributeManager
     *
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
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract)
    {
        $this->touchFacade->touchActive('product_abstract', $idProductAbstract);
    }

    /**
     * @param string $sku
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createProductUrl($sku, $url, LocaleTransfer $locale)
    {
        $idProductAbstract = $this->getProductAbstractIdBySku($sku);

        return $this->createProductUrlByIdProduct($idProductAbstract, $url, $locale);
    }

    /**
     * @param int $idProductAbstract
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale)
    {
        return $this->urlFacade->createUrl($url, $locale, 'product_abstract', $idProductAbstract);
    }

    /**
     * @param string $sku
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrl($sku, $url, LocaleTransfer $locale)
    {
        $url = $this->createProductUrl($sku, $url, $locale);
        $this->urlFacade->touchUrlActive($url->getIdUrl());

        return $url;
    }

    /**
     * @param int $idProductAbstract
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale)
    {
        $urlTransfer = $this->createProductUrlByIdProduct($idProductAbstract, $url, $locale);
        $this->urlFacade->touchUrlActive($urlTransfer->getIdUrl());

        return $urlTransfer;
    }

    /**
     * TODO Move to TaxManager
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return float
     */
    public function getEffectiveTaxRateForProductConcrete($sku)
    {
        $productConcrete = $this->productQueryContainer
            ->queryProductConcreteBySku($sku)
            ->findOne();

        if (!$productConcrete) {
            throw new MissingProductException(sprintf(
                'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                $sku
            ));
        }

        $productAbstract = $productConcrete->getSpyProductAbstract();

        $taxSetEntity = $productAbstract->getSpyTaxSet();
        if ($taxSetEntity === null) {
            return 0;
        }

        $effectiveTaxRate = $this->getEffectiveTaxRate($taxSetEntity->getSpyTaxRates());

        return $effectiveTaxRate;
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
            throw new MissingProductException(sprintf(
                'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                $sku
            ));
        }

        return $productConcrete->getFkProductAbstract();
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
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

        return $productAbstractTransfer;
    }

    /**
     * TODO move to AttributeManager
     *
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
     * @param int $idProduct
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
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

        return $productTransfer;
    }

    /**
     * TODO move to AttributeManager
     *
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
            throw new MissingProductException(sprintf(
                'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                $sku
            ));
        }

        return $productConcrete->getSpyProductAbstract()->getSku();
    }

    /**
     * TODO move to AttributeManager
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function encodeAttributes(array $attributes)
    {
        return Json::encode($attributes);
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate[] $taxRates
     *
     * @return int
     */
    protected function getEffectiveTaxRate($taxRates)
    {
        $taxRate = 0;
        foreach ($taxRates as $taxRateEntity) {
            $taxRate += $taxRateEntity->getRate();
        }

        return $taxRate;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $productConcreteCollection
     *
     * @throws \Exception
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        try {
            $idProductAbstract = $this->createProductAbstract($productAbstractTransfer);
            $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

            foreach ($productConcreteCollection as $productConcrete) {
                $this->createProductConcrete($productConcrete, $idProductAbstract);
            }

            $this->productQueryContainer->getConnection()->commit();

            return $idProductAbstract;

        } catch (\Exception $e) {
            $this->productQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array|\Generated\Shared\Transfer\ZedProductConcreteTransfer[] $productConcreteCollection
     *
     * @throws \Exception
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        try {
            $idProductAbstract = $this->saveProductAbstract($productAbstractTransfer);

            foreach ($productConcreteCollection as $productConcreteTransfer) {
                $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

                $productConcreteEntity = $this->findProductConcreteId($productAbstractTransfer, $productConcreteTransfer);
                if ($productConcreteEntity) {
                    $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
                    $this->saveProductConcrete($productConcreteTransfer);
                } else {
                    $this->createProductConcrete($productConcreteTransfer, $idProductAbstract);
                }
            }

            $this->productQueryContainer->getConnection()->commit();

            return $idProductAbstract;

        } catch (\Exception $e) {
            $this->productQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * TODO move to AttributeManager
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ZedProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function findProductConcreteId(ProductAbstractTransfer $productAbstractTransfer, ZedProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOne();
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

        for ($a=0; $a<count($transferCollection); $a++) {
            $transferCollection[$a] = $this->loadProductConcreteLocalizedAttributes($transferCollection[$a]);
            $transferCollection[$a] = $this->loadPriceForProductConcrete($transferCollection[$a]);
            $transferCollection[$a] = $this->loadStockForProductConcrete($transferCollection[$a]);
        }

        return $transferCollection;
    }

    /**
     * TODO Move it to AttributeManager
     *
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

        $concreteProductCollection = $this->getConcreteProductsByAbstractProductId($idProductAbstract);
        $abstractLocalizedAttributes = [];

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            /* @var \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttribute */
            $localeCode = $localizedAttribute->getLocale()->getLocaleName();
            if (!array_key_exists($localeCode, $abstractLocalizedAttributes)) {
                $abstractLocalizedAttributes[$localeCode] = $localizedAttribute->getAttributes();
            } else {
                $abstractLocalizedAttributes[$localeCode] = array_merge($abstractLocalizedAttributes[$localeCode], $localizedAttribute->getAttributes());
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
                    $localizedAttributes[$localeCode] = array_merge($localizedAttributes[$localeCode], $localizedAttribute->getAttributes());
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

}
