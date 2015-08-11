<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Product;

use Generated\Shared\Product\AbstractProductInterface;
use Generated\Shared\Product\ConcreteProductInterface;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedAbstractProductAttributes;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedProductAttributes;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;

class ProductManager implements ProductManagerInterface
{
    const COL_ID_CONCRETE_PRODUCT = 'SpyProduct.IdProduct';

    const COL_ABSTRACT_SKU = 'SpyAbstractProduct.Sku';

    const COL_ID_ABSTRACT_PRODUCT = 'SpyAbstractProduct.IdAbstractProduct';

    const COL_NAME = 'SpyLocalizedProductAttributes.Name';

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
     * @var array
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
    public function hasAbstractProduct($sku)
    {
        if (!isset($this->concreteProductsBySkuCache[$sku]['abstractCount'])) {
            $abstractProductQuery = $this->productQueryContainer->queryAbstractProductBySku($sku);

            $this->concreteProductsBySkuCache[$sku]['abstractCount'] = $abstractProductQuery->count();
        }

        return $this->concreteProductsBySkuCache[$sku]['abstractCount'] > 0;
    }

    /**
     * @param AbstractProductInterface $abstractProductTransfer
     *
     * @throws AbstractProductExistsException
     * @throws PropelException
     *
     * @return int
     */
    public function createAbstractProduct(AbstractProductInterface $abstractProductTransfer)
    {
        $sku = $abstractProductTransfer->getSku();

        $this->checkAbstractProductDoesNotExist($sku);
        $encodedAttributes = $this->encodeAttributes($abstractProductTransfer->getAttributes());

        $abstractProduct = new SpyAbstractProduct();
        $abstractProduct
            ->setAttributes($encodedAttributes)
            ->setSku($sku)
        ;

        $abstractProduct->save();

        $idAbstractProduct = $abstractProduct->getPrimaryKey();
        $abstractProductTransfer->setIdAbstractProduct($idAbstractProduct);
        $this->createAbstractProductAttributes($abstractProductTransfer);

        return $idAbstractProduct;
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku)
    {
        if (!isset($this->concreteProductsBySkuCache[$sku]['abstractProduct'])) {
            $abstractProduct = $this->productQueryContainer->queryAbstractProductBySku($sku)->findOne();

            if (!$abstractProduct) {
                throw new MissingProductException(
                    sprintf(
                        'Tried to retrieve an abstract product with sku %s, but it does not exist.',
                        $sku
                    )
                );
            }

            $this->concreteProductsBySkuCache[$sku]['abstractProduct'] = $abstractProduct;
        }

        return $this->concreteProductsBySkuCache[$sku]['abstractProduct']->getPrimaryKey();
    }

    /**
     * @param string $sku
     *
     * @throws AbstractProductExistsException
     */
    protected function checkAbstractProductDoesNotExist($sku)
    {
        if ($this->hasAbstractProduct($sku)) {
            throw new AbstractProductExistsException(
                sprintf(
                    'Tried to create an abstract product with sku %s that already exists',
                    $sku
                )
            );
        }
    }

    /**
     * @param AbstractProductInterface $abstractProductTransfer
     * @throws AbstractProductAttributesExistException
     * @throws PropelException
     */
    protected function createAbstractProductAttributes(AbstractProductInterface $abstractProductTransfer)
    {
        $idAbstractProduct = $abstractProductTransfer->getIdAbstractProduct();

        foreach ($abstractProductTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            //$this->checkAbstractProductAttributesDoNotExist($idAbstractProduct, $locale);
            if ($this->hasAbstractProductAttributes($idAbstractProduct, $locale)) {
                continue;
            }
            $encodedAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $abstractProductAttributesEntity = new SpyLocalizedAbstractProductAttributes();
            $abstractProductAttributesEntity
                ->setFkAbstractProduct($idAbstractProduct)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setAttributes($encodedAttributes)
            ;
            $abstractProductAttributesEntity->save();
        }
    }

    /**
     * @param int $idAbstractProduct
     * @param LocaleTransfer $locale
     * @deprecated Use hasAbstractProductAttributes() instead.
     *
     * @throws AbstractProductAttributesExistException
     */
    protected function checkAbstractProductAttributesDoNotExist($idAbstractProduct, $locale)
    {
        if ($this->hasAbstractProductAttributes($idAbstractProduct, $locale)) {
            throw new AbstractProductAttributesExistException(
                sprintf(
                    'Tried to create abstract attributes for abstract product %s, locale id %s, but it already exists',
                    $idAbstractProduct,
                    $locale->getIdLocale()
                )
            );
        }
    }

    /**
     * @param int $idAbstractProduct
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    protected function hasAbstractProductAttributes($idAbstractProduct, LocaleTransfer $locale)
    {
        $query = $this->productQueryContainer->queryAbstractProductAttributeCollection(
            $idAbstractProduct,
            $locale->getIdLocale()
        );

        return $query->count() > 0;
    }

    /**
     * @param ConcreteProductInterface $concreteProductTransfer
     * @param int $idAbstractProduct
     *
     * @throws ConcreteProductExistsException
     * @throws PropelException
     *
     * @return int
     */
    public function createConcreteProduct(ConcreteProductInterface $concreteProductTransfer, $idAbstractProduct)
    {
        $sku = $concreteProductTransfer->getSku();

        $this->checkConcreteProductDoesNotExist($sku);
        $encodedAttributes = $this->encodeAttributes($concreteProductTransfer->getAttributes());

        $concreteProductEntity = new SpyProduct();
        $concreteProductEntity
            ->setSku($sku)
            ->setFkAbstractProduct($idAbstractProduct)
            ->setAttributes($encodedAttributes)
            ->setIsActive($concreteProductTransfer->getIsActive())
        ;

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
        if (!isset($this->concreteProductsBySkuCache[$sku]['concreteCount'])) {
            $query = $this->productQueryContainer->queryConcreteProductBySku($sku);

            $this->concreteProductsBySkuCache[$sku]['concreteCount'] = $query->count();
        }

        return $this->concreteProductsBySkuCache[$sku]['concreteCount'] > 0;
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
        if (!isset($this->concreteProductsBySkuCache[$sku]['concreteProduct'])) {
            $concreteProduct = $this->productQueryContainer->queryConcreteProductBySku($sku)->findOne();

            if (!$concreteProduct) {
                throw new MissingProductException(
                    sprintf(
                        'Tried to retrieve a concrete product with sku %s, but it does not exist',
                        $sku
                    )
                );
            }

            $this->concreteProductsBySkuCache[$sku]['concreteProduct'] = $concreteProduct;
        }

        return $this->concreteProductsBySkuCache[$sku]['concreteProduct']->getPrimaryKey();
    }

    /**
     * @param ConcreteProductInterface $concreteProductTransfer
     * @throws ConcreteProductAttributesExistException
     * @throws PropelException
     */
    protected function createConcreteProductAttributes(ConcreteProductInterface $concreteProductTransfer)
    {
        $idConcreteProduct = $concreteProductTransfer->getIdConcreteProduct();

        foreach ($concreteProductTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $locale = $localizedAttributes->getLocale();
            $this->checkConcreteProductAttributesDoNotExist($idConcreteProduct, $locale);
            $encodedAttributes = $this->encodeAttributes($localizedAttributes->getAttributes());

            $productAttributeEntity = new SpyLocalizedProductAttributes();
            $productAttributeEntity
                ->setFkProduct($idConcreteProduct)
                ->setFkLocale($locale->getIdLocale())
                ->setName($localizedAttributes->getName())
                ->setAttributes($encodedAttributes)
            ;

            $productAttributeEntity->save();
        }
    }

    /**
     * @param int $idConcreteProduct
     * @param LocaleTransfer $locale
     *
     * @throws ConcreteProductAttributesExistException
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
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct)
    {
        $this->touchFacade->touchActive('abstract_product', $idAbstractProduct);
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
        $idAbstractProduct = $this->getAbstractProductIdBySku($sku);

        return $this->createProductUrlByIdProduct($idAbstractProduct, $url, $locale);
    }

    /**
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createProductUrlByIdProduct($idAbstractProduct, $url, LocaleTransfer $locale)
    {
        return $this->urlFacade->createUrl($url, $locale, 'abstract_product', $idAbstractProduct);
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
     * @param int $idAbstractProduct
     * @param string $url
     * @param LocaleTransfer $locale
     *
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     *
     * @return UrlTransfer
     */
    public function createAndTouchProductUrlByIdProduct($idAbstractProduct, $url, LocaleTransfer $locale)
    {
        $url = $this->createProductUrlByIdProduct($idAbstractProduct, $url, $locale);
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

        $abstractProduct = $concreteProduct->getSpyAbstractProduct();

        $effectiveTaxRate = 0.0;

        $taxSetEntity = $abstractProduct->getSpyTaxSet();
        if (null === $taxSetEntity) {
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
     * @return ConcreteProductInterface
     */
    public function getConcreteProduct($concreteSku)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $concreteProduct = $this->productQueryContainer->queryProductWithAttributesAndAbstractProduct(
            $concreteSku, $localeTransfer->getIdLocale()
        )->select([
            self::COL_ID_CONCRETE_PRODUCT,
            self::COL_ABSTRACT_SKU,
            self::COL_ID_ABSTRACT_PRODUCT,
            self::COL_NAME
        ])->findOne();

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
            ->setAbstractProductSku($concreteProduct[self::COL_ABSTRACT_SKU])
            ->setIdAbstractProduct($concreteProduct[self::COL_ID_ABSTRACT_PRODUCT])
            ->setName($concreteProduct[self::COL_NAME]);

        $this->addTaxesToProductTransfer($concreteProductTransfer);

        return $concreteProductTransfer;
    }

    /**
     * @param ConcreteProductInterface $productTransfer
     */
    private function addTaxesToProductTransfer(ConcreteProductInterface $concreteProductTransfer)
    {
        $taxSetEntity = $this->productQueryContainer
            ->queryTaxSetForAbstractProduct($concreteProductTransfer->getIdAbstractProduct())
            ->findOne();

        if (null === $taxSetEntity) {
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
    public function getAbstractProductIdByConcreteSku($sku)
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

        return $concreteProduct->getFkAbstractProduct();
    }

    /**
     * @param string $sku
     *
     * @return string
     * @throws MissingProductException
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

        return $concreteProduct->getSpyAbstractProduct()->getSku();
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
