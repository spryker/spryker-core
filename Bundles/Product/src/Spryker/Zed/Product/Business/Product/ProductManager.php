<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractAttributesExistException;
use Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException;
use Spryker\Zed\Product\Business\Exception\ConcreteProductAttributesExistException;
use Spryker\Zed\Product\Business\Exception\ConcreteProductExistsException;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;

class ProductManager implements ProductManagerInterface
{

    const COL_ID_CONCRETE_PRODUCT = 'SpyProduct.IdProduct';

    const COL_ABSTRACT_SKU = 'SpyProductAbstract.Sku';

    const COL_ID_PRODUCT_ABSTRACT = 'SpyProductAbstract.IdProductAbstract';

    const COL_NAME = 'SpyProductLocalizedAttributes.Name';

    /**
     * @var ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var SpyProductAbstract[]
     */
    protected $productAbstractCollectionBySkuCache = [];

    /**
     * @var SpyProduct[]
     */
    protected $concreteProductsBySkuCache = [];

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
     * @param ProductToTouchInterface $touchFacade
     * @param ProductToUrlInterface $urlFacade
     * @param ProductToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToUrlInterface $urlFacade,
        ProductToLocaleInterface $localeFacade
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
        $this->localeFacade = $localeFacade;
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws ProductAbstractExistsException
     * @throws PropelException
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $sku = $productAbstractTransfer->getSku();

        $encodedAttributes = $this->encodeAttributes($productAbstractTransfer->getAttributes());

        $productAbstract = new SpyProductAbstract();
        $productAbstract
            ->setAttributes($encodedAttributes)
            ->setSku($sku);

        $productAbstract->save();

        $idProductAbstract = $productAbstract->getPrimaryKey();
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->createProductAbstractAttributes($productAbstractTransfer);

        return $idProductAbstract;
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku)
    {
        if (!isset($this->productAbstractsBySkuCache[$sku])) {
            $productAbstract = $this->productQueryContainer->queryProductAbstractBySku($sku)->findOne();

            if (!$productAbstract) {
                throw new MissingProductException(
                    sprintf(
                        'Tried to retrieve an abstract product with sku %s, but it does not exist.',
                        $sku
                    )
                );
            }

            $this->productAbstractsBySkuCache[$sku] = $productAbstract;
        }

        return $this->productAbstractsBySkuCache[$sku]->getPrimaryKey();
    }

    /**
     * @param string $sku
     *
     * @throws ProductAbstractExistsException
     *
     * @return void
     */
    protected function checkProductAbstractDoesNotExist($sku)
    {
        if ($this->hasProductAbstract($sku)) {
            throw new ProductAbstractExistsException(
                sprintf(
                    'Tried to create an abstract product with sku %s that already exists',
                    $sku
                )
            );
        }
    }

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws ProductAbstractAttributesExistException
     * @throws PropelException
     *
     * @return void
     */
    protected function createProductAbstractAttributes(ProductAbstractTransfer $productAbstractTransfer)
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
                ->setAttributes($encodedAttributes);

            $productAbstractAttributesEntity->save();
        }
    }

    /**
     * @param int $idProductAbstract
     * @param LocaleTransfer $locale
     *
     * @deprecated Use hasProductAbstractAttributes() instead.
     *
     * @throws ProductAbstractAttributesExistException
     *
     * @return void
     */
    protected function checkProductAbstractAttributesDoNotExist($idProductAbstract, $locale)
    {
        if ($this->hasProductAbstractAttributes($idProductAbstract, $locale)) {
            throw new ProductAbstractAttributesExistException(
                sprintf(
                    'Tried to create abstract attributes for abstract product %s, locale id %s, but it already exists',
                    $idProductAbstract,
                    $locale->getIdLocale()
                )
            );
        }
    }

    /**
     * @param int $idProductAbstract
     * @param LocaleTransfer $locale
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
     * @param ConcreteProductTransfer $concreteProductTransfer
     * @param int $idProductAbstract
     *
     * @throws ConcreteProductExistsException
     * @throws PropelException
     *
     * @return int
     */
    public function createConcreteProduct(ConcreteProductTransfer $concreteProductTransfer, $idProductAbstract)
    {
        $sku = $concreteProductTransfer->getSku();

        $this->checkConcreteProductDoesNotExist($sku);
        $encodedAttributes = $this->encodeAttributes($concreteProductTransfer->getAttributes());

        $concreteProductEntity = new SpyProduct();
        $concreteProductEntity
            ->setSku($sku)
            ->setFkProductAbstract($idProductAbstract)
            ->setAttributes($encodedAttributes)
            ->setIsActive($concreteProductTransfer->getIsActive());

        $concreteProductEntity->save();

        $idConcreteProduct = $concreteProductEntity->getPrimaryKey();
        $concreteProductTransfer->setIdConcreteProduct($idConcreteProduct);
        $this->createConcreteProductAttributes($concreteProductTransfer);

        return $idConcreteProduct;
    }

    /**
     * @param string $sku
     *
     * @throws ConcreteProductExistsException
     *
     * @return void
     */
    protected function checkConcreteProductDoesNotExist($sku)
    {
        if ($this->hasConcreteProduct($sku)) {
            throw new ConcreteProductExistsException(
                sprintf(
                    'Tried to create a concrete product with sku %s, but it already exists',
                    $sku
                )
            );
        }
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku)
    {
        return $this->productQueryContainer->queryConcreteProductBySku($sku)->count() > 0;
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku)
    {
        if (!isset($this->concreteProductsBySkuCache[$sku])) {
            $concreteProduct = $this->productQueryContainer->queryConcreteProductBySku($sku)->findOne();

            if (!$concreteProduct) {
                throw new MissingProductException(
                    sprintf(
                        'Tried to retrieve a concrete product with sku %s, but it does not exist',
                        $sku
                    )
                );
            }

            $this->concreteProductsBySkuCache[$sku] = $concreteProduct;
        }

        return $this->concreteProductsBySkuCache[$sku]->getPrimaryKey();
    }

    /**
     * @param ConcreteProductTransfer $concreteProductTransfer
     *
     * @throws ConcreteProductAttributesExistException
     * @throws PropelException
     *
     * @return void
     */
    protected function createConcreteProductAttributes(ConcreteProductTransfer $concreteProductTransfer)
    {
        $idConcreteProduct = $concreteProductTransfer->getIdConcreteProduct();

        foreach ($concreteProductTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $this->checkConcreteProductAttributesDoNotExist($idConcreteProduct, $locale);
            $encodedAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $productAttributeEntity = new SpyProductLocalizedAttributes();
            $productAttributeEntity
                ->setFkProduct($idConcreteProduct)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setAttributes($encodedAttributes);

            $productAttributeEntity->save();
        }
    }

    /**
     * @param int $idConcreteProduct
     * @param LocaleTransfer $locale
     *
     * @throws ConcreteProductAttributesExistException
     *
     * @return void
     */
    protected function checkConcreteProductAttributesDoNotExist($idConcreteProduct, LocaleTransfer $locale)
    {
        if ($this->hasConcreteProductAttributes($idConcreteProduct, $locale)) {
            throw new ConcreteProductAttributesExistException(
                sprintf(
                    'Tried to create concrete product attributes for product id %s, locale id %s, but they exist',
                    $idConcreteProduct,
                    $locale->getIdLocale()
                )
            );
        }
    }

    /**
     * @param int $idConcreteProduct
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    protected function hasConcreteProductAttributes($idConcreteProduct, LocaleTransfer $locale)
    {
        $query = $this->productQueryContainer->queryConcreteProductAttributeCollection(
            $idConcreteProduct,
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
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createProductUrl($sku, $url, LocaleTransfer $locale)
    {
        $idProductAbstract = $this->getProductAbstractIdBySku($sku);

        return $this->createProductUrlByIdProduct($idProductAbstract, $url, $locale);
    }

    /**
     * @param int $idProductAbstract
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale)
    {
        return $this->urlFacade->createUrl($url, $locale, 'product_abstract', $idProductAbstract);
    }

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
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
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idProductAbstract, $url, LocaleTransfer $locale)
    {
        $url = $this->createProductUrlByIdProduct($idProductAbstract, $url, $locale);
        $this->urlFacade->touchUrlActive($url->getIdUrl());

        return $url;
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return float
     */
    public function getEffectiveTaxRateForConcreteProduct($sku)
    {
        $concreteProduct = $this->productQueryContainer->queryConcreteProductBySku($sku)->findOne();

        if (!$concreteProduct) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a concrete product with sku %s, but it does not exist.',
                    $sku
                )
            );
        }

        $productAbstract = $concreteProduct->getSpyProductAbstract();

        $effectiveTaxRate = 0;

        $taxSetEntity = $productAbstract->getSpyTaxSet();
        if ($taxSetEntity === null) {
            return $effectiveTaxRate;
        }

        foreach ($taxSetEntity->getSpyTaxRates() as $taxRateEntity) {
            $effectiveTaxRate += $taxRateEntity->getRate();
        }

        return $effectiveTaxRate;
    }

    /**
     * @param string $concreteSku
     *
     * @throws MissingProductException
     *
     * @return ConcreteProductTransfer
     */
    public function getConcreteProduct($concreteSku)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $concreteProductQuery = $this->productQueryContainer->queryProductWithAttributesAndProductAbstract(
            $concreteSku, $localeTransfer->getIdLocale()
        );

        $concreteProductQuery->select([
            self::COL_ID_CONCRETE_PRODUCT,
            self::COL_ABSTRACT_SKU,
            self::COL_ID_PRODUCT_ABSTRACT,
            self::COL_NAME,
        ]);

        $concreteProduct = $concreteProductQuery->findOne();

        if (!$concreteProduct) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a concrete product with sku %s, but it does not exist.',
                    $concreteSku
                )
            );
        }

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setSku($concreteSku)
            ->setIdConcreteProduct($concreteProduct[self::COL_ID_CONCRETE_PRODUCT])
            ->setProductAbstractSku($concreteProduct[self::COL_ABSTRACT_SKU])
            ->setIdProductAbstract($concreteProduct[self::COL_ID_PRODUCT_ABSTRACT])
            ->setName($concreteProduct[self::COL_NAME]);

        $this->addTaxesToProductTransfer($concreteProductTransfer);

        return $concreteProductTransfer;
    }

    /**
     * @param ConcreteProductTransfer $concreteProductTransfer
     *
     * @return void
     */
    private function addTaxesToProductTransfer(ConcreteProductTransfer $concreteProductTransfer)
    {
        $taxSetEntity = $this->productQueryContainer
            ->queryTaxSetForProductAbstract($concreteProductTransfer->getIdProductAbstract())
            ->findOne();

        if ($taxSetEntity === null) {
            return;
        }

        $taxTransfer = new TaxSetTransfer();
        $taxTransfer->setIdTaxSet($taxSetEntity->getIdTaxSet())
            ->setName($taxSetEntity->getName());

        foreach ($taxSetEntity->getSpyTaxRates() as $taxRate) {
            $taxRateTransfer = new TaxRateTransfer();
            $taxRateTransfer->setIdTaxRate($taxRate->getIdTaxRate())
                ->setName($taxRate->getName())
                ->setRate($taxRate->getRate());

            $taxTransfer->addTaxRate($taxRateTransfer);
        }

        $concreteProductTransfer->setTaxSet($taxTransfer);
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku)
    {
        $concreteProduct = $this->productQueryContainer->queryConcreteProductBySku($sku)->findOne();

        if (!$concreteProduct) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a concrete product with sku %s, but it does not exist.',
                    $sku
                )
            );
        }

        return $concreteProduct->getFkProductAbstract();
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return string
     */
    public function getAbstractSkuFromConcreteProduct($sku)
    {
        $concreteProduct = $this->productQueryContainer->queryConcreteProductBySku($sku)->findOne();

        if (!$concreteProduct) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a concrete product with sku %s, but it does not exist.',
                    $sku
                )
            );
        }

        return $concreteProduct->getSpyProductAbstract()->getSku();
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    protected function encodeAttributes(array $attributes)
    {
        return json_encode($attributes);
    }

}
