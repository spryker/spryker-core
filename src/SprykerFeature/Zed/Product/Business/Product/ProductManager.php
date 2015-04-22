<?php

namespace SprykerFeature\Zed\Product\Business\Product;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use SprykerFeature\Shared\Url\Transfer\Url;
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
     * @param int $fkLocale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws AbstractProductAttributesExistException
     */
    public function createAbstractProductAttributes($idAbstractProduct, $fkLocale, $name, $attributes)
    {
        $this->checkAbstractProductAttributesDoNotExist($idAbstractProduct, $fkLocale);

        $abstractProductAttributesEntity = $this->locator->product()->entitySpyLocalizedAbstractProductAttributes();
        $abstractProductAttributesEntity
            ->setFkAbstractProduct($idAbstractProduct)
            ->setFkLocale($fkLocale)
            ->setName($name)
            ->setAttributes($attributes)
        ;

        $abstractProductAttributesEntity->save();

        return $abstractProductAttributesEntity->getPrimaryKey();
    }

    /**
     * @param int $idAbstractProduct
     * @param int $fkCurrentLocale
     *
     * @throws AbstractProductAttributesExistException
     */
    protected function checkAbstractProductAttributesDoNotExist($idAbstractProduct, $fkCurrentLocale)
    {
        if ($this->hasAbstractProductAttributes($idAbstractProduct, $fkCurrentLocale)) {
            throw new AbstractProductAttributesExistException(
                sprintf(
                    'Tried to create abstract attributes for abstract product %s, locale id %s, but it already exists',
                    $idAbstractProduct,
                    $fkCurrentLocale
                )
            );
        }
    }

    /**
     * @param int $idAbstractProduct
     * @param int $fkCurrentLocale
     *
     * @return bool
     */
    protected function hasAbstractProductAttributes($idAbstractProduct, $fkCurrentLocale)
    {
        $query = $this->productQueryContainer->queryAbstractProductAttributeCollection($idAbstractProduct, $fkCurrentLocale);

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
     * @param int $fkLocale
     * @param string $name
     * @param string $attributes
     *
     * @return int
     * @throws ConcreteProductAttributesExistException
     */
    public function createConcreteProductAttributes($idConcreteProduct, $fkLocale, $name, $attributes)
    {
        $this->checkConcreteProductAttributesDoNotExist($idConcreteProduct, $fkLocale);

        $productAttributeEntity = $this->locator->product()->entitySpyLocalizedProductAttributes();
        $productAttributeEntity
            ->setFkProduct($idConcreteProduct)
            ->setFkLocale($fkLocale)
            ->setName($name)
            ->setAttributes($attributes)
        ;

        $productAttributeEntity->save();

        return $productAttributeEntity->getPrimaryKey();
    }

    /**
     * @param int $idConcreteProduct
     * @param int $fkCurrentLocale
     *
     * @throws ConcreteProductAttributesExistException
     */
    protected function checkConcreteProductAttributesDoNotExist($idConcreteProduct, $fkCurrentLocale)
    {
        if ($this->hasConcreteProductAttributes($idConcreteProduct, $fkCurrentLocale)) {
            throw new ConcreteProductAttributesExistException(
                sprintf(
                    'Tried to create concrete product attributes for product id %s, locale id %s, but they exist',
                    $idConcreteProduct,
                    $fkCurrentLocale
                )
            );
        }
    }

    /**
     * @param int $idConcreteProduct
     * @param int $fkCurrentLocale
     *
     * @return bool
     */
    protected function hasConcreteProductAttributes($idConcreteProduct, $fkCurrentLocale)
    {
        $query = $this->productQueryContainer->queryConcreteProductAttributeCollection($idConcreteProduct, $fkCurrentLocale);

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
     * @param string $localeName
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrl($sku, $url, $localeName)
    {
        $idConcreteProduct = $this->getConcreteProductIdBySku($sku);

        return $this->urlFacade->createUrl($url, $localeName, 'product', $idConcreteProduct);
    }

    /**
     * @param int $idConcreteProduct
     * @param string $url
     * @param int $idLocale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createProductUrlByIds($idConcreteProduct, $url, $idLocale)
    {
        return $this->urlFacade->createUrlByLocaleFk($url, $idLocale, 'product', $idConcreteProduct);
    }

    /**
     * @param string $sku
     * @param string $url
     * @param string $localeName
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrl($sku, $url, $localeName)
    {
        $url = $this->createProductUrl($sku, $url, $localeName);
        $this->urlFacade->touchUrlActive($url->getIdUrl());

        return $url;
    }

    /**
     * @param int $idConcreteProduct
     * @param string $url
     * @param int $idLocale
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     * @throws MissingProductException
     */
    public function createAndTouchProductUrlByIds($idConcreteProduct, $url, $idLocale)
    {
        $url = $this->createProductUrlByIds($idConcreteProduct, $url, $idLocale);
        $this->urlFacade->touchUrlActive($url->getIdUrl());

        return $url;
    }
}
