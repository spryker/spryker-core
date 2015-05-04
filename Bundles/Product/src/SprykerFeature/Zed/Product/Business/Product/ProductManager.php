<?php

namespace SprykerFeature\Zed\Product\Business\Product;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
     * @param ProductToTouchInterface $touchFacade
     * @param ProductToUrlInterface $urlFacade
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToUrlInterface $urlFacade,
        LocatorLocatorInterface $locator
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->locator = $locator;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku)
    {
        $abstractProductQuery = $this->productQueryContainer->queryAbstractProductBySku($sku);

        return $abstractProductQuery->count() > 0;
    }

    /**
     * @param string $sku
     *
     * @return int
     * @throws AbstractProductExistsException
     */
    public function createAbstractProduct($sku)
    {
        $this->checkAbstractProductDoesNotExist($sku);

        $abstractProduct = $this->locator->product()->entitySpyAbstractProduct()
            ->setSku($sku)
        ;

        $abstractProduct->save();

        return $abstractProduct->getPrimaryKey();
    }

    /**
     * @param string $sku
     * @return int
     *
     * @throws MissingProductException
     */
    public function getAbstractProductIdBySku($sku)
    {
        $abstractProduct = $this->productQueryContainer->queryAbstractProductBySku($sku)->findOne();

        if (!$abstractProduct) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve an abstract product with sku %s, but it does not exist.',
                    $sku
                )
            );
        }

        return $abstractProduct->getPrimaryKey();
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
     * @param int $idAbstractProduct
     * @param LocaleDto $locale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws AbstractProductAttributesExistException
     */
    public function createAbstractProductAttributes($idAbstractProduct, LocaleDto $locale, $name, $attributes)
    {
        $this->checkAbstractProductAttributesDoNotExist($idAbstractProduct, $locale);

        $abstractProductAttributesEntity = $this->locator->product()->entitySpyLocalizedAbstractProductAttributes();
        $abstractProductAttributesEntity
            ->setFkAbstractProduct($idAbstractProduct)
            ->setFkLocale($locale->getIdLocale())
            ->setName($name)
            ->setAttributes($attributes)
        ;

        $abstractProductAttributesEntity->save();

        return $abstractProductAttributesEntity->getPrimaryKey();
    }

    /**
     * @param int $idAbstractProduct
     * @param LocaleDto $locale
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
     * @param LocaleDto $locale
     *
     * @return bool
     */
    protected function hasAbstractProductAttributes($idAbstractProduct, LocaleDto $locale)
    {
        $query = $this->productQueryContainer->queryAbstractProductAttributeCollection($idAbstractProduct, $locale->getIdLocale());

        return $query->count() > 0;
    }

    /**
     * @param string $sku
     * @param int $idAbstractProduct
     * @param bool $isActive
     *
     * @return int
     * @throws ConcreteProductExistsException
     */
    public function createConcreteProduct($sku, $idAbstractProduct, $isActive = true)
    {
        $this->checkConcreteProductDoesNotExist($sku);
        $concreteProductEntity = $this->locator->product()->entitySpyProduct();

        $concreteProductEntity
            ->setSku($sku)
            ->setFkAbstractProduct($idAbstractProduct)
            ->setIsActive($isActive)
        ;

        $concreteProductEntity->save();

        return $concreteProductEntity->getPrimaryKey();
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
        $query = $this->productQueryContainer->queryConcreteProductBySku($sku);

        return $query->count() > 0;
    }

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getConcreteProductIdBySku($sku)
    {
        $concreteProduct = $this->productQueryContainer->queryConcreteProductBySku($sku)->findOne();

        if (!$concreteProduct) {
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a concrete product with sku %s, but it does not exist',
                    $sku
                )
            );
        }

        return $concreteProduct->getPrimaryKey();
    }

    /**
     * @param int $idConcreteProduct
     * @param LocaleDto $locale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws ConcreteProductAttributesExistException
     */
    public function createConcreteProductAttributes($idConcreteProduct, LocaleDto $locale, $name, $attributes)
    {
        $this->checkConcreteProductAttributesDoNotExist($idConcreteProduct, $locale);

        $productAttributeEntity = $this->locator->product()->entitySpyLocalizedProductAttributes();
        $productAttributeEntity
            ->setFkProduct($idConcreteProduct)
            ->setFkLocale($locale->getIdLocale())
            ->setName($name)
            ->setAttributes($attributes)
        ;

        $productAttributeEntity->save();

        return $productAttributeEntity->getPrimaryKey();
    }

    /**
     * @param int $idConcreteProduct
     * @param LocaleDto $locale
     *
     * @throws ConcreteProductAttributesExistException
     */
    protected function checkConcreteProductAttributesDoNotExist($idConcreteProduct, LocaleDto $locale)
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
     * @param LocaleDto $locale
     *
     * @return bool
     */
    protected function hasConcreteProductAttributes($idConcreteProduct, LocaleDto $locale)
    {
        $query = $this->productQueryContainer->queryConcreteProductAttributeCollection($idConcreteProduct, $locale->getIdLocale());

        return $query->count() > 0;
    }

    /**
     * @param int $idConcreteProduct
     */
    public function touchProductActive($idConcreteProduct)
    {
        $this->touchFacade->touchActive('product', $idConcreteProduct);
    }

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrl($sku, $url, LocaleDto $locale)
    {
        $idConcreteProduct = $this->getConcreteProductIdBySku($sku);

        return $this->createProductUrlByIdProduct($idConcreteProduct, $url, $locale);
    }

    /**
     * @param int $idConcreteProduct
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrlByIdProduct($idConcreteProduct, $url, LocaleDto $locale)
    {
        return $this->urlFacade->createUrl($url, $locale, 'product', $idConcreteProduct);
    }

    /**
     * @param string $sku
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrl($sku, $url, LocaleDto $locale)
    {
        $url = $this->createProductUrl($sku, $url, $locale);
        $this->urlFacade->touchUrlActive($url->getIdUrl());

        return $url;
    }

    /**
     * @param int $idConcreteProduct
     * @param string $url
     * @param LocaleDto $locale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrlByIdProduct($idConcreteProduct, $url, LocaleDto $locale)
    {
        $url = $this->createProductUrlByIdProduct($idConcreteProduct, $url, $locale);
        $this->urlFacade->touchUrlActive($url->getIdUrl());

        return $url;
    }
}
