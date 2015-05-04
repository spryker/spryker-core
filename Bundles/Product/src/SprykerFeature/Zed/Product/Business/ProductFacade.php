<?php

namespace SprykerFeature\Zed\Product\Business;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\AbstractProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\AttributeExistsException;
use SprykerFeature\Zed\Product\Business\Exception\AttributeTypeExistsException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductAttributesExistException;
use SprykerFeature\Zed\Product\Business\Exception\ConcreteProductExistsException;
use SprykerFeature\Zed\Product\Business\Exception\MissingAttributeTypeException;
use SprykerFeature\Zed\Product\Business\Exception\MissingProductException;
use SprykerFeature\Zed\Product\Business\Model\ProductBatchResult;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @method ProductDependencyContainer getDependencyContainer()
 */
class ProductFacade extends AbstractFacade
{
    /**
     * @param \SplFileInfo $file
     *
     * @return ProductBatchResult
     */
    public function importProductsFromFile(\SplFileInfo $file)
    {
        return $this->getDependencyContainer()
            ->createProductImporter()
            ->importFile($file);
    }

    /**
     * @param string $uploadedFilename
     *
     * @return \SplFileInfo
     */
    public function importUploadedFile($uploadedFilename)
    {
        return $this->getDependencyContainer()
            ->createHttpFileImporter()
            ->receiveUploadedFile($uploadedFilename);
    }

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getAbstractProductIdBySku($sku)
    {
        return $this->getDependencyContainer()->createProductManager()->getAbstractProductIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     * @throws MissingProductException
     */
    public function getConcreteProductIdBySku($sku)
    {
        return $this->getDependencyContainer()->createProductManager()->getConcreteProductIdBySku($sku);
    }

    /**
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute($attributeName)
    {
        $attributeManager = $this->getDependencyContainer()->createAttributeManager();

        return $attributeManager->hasAttribute($attributeName);
    }

    /**
     * @param string $attributeType
     *
     * @return bool
     */
    public function hasAttributeType($attributeType)
    {
        $attributeManager = $this->getDependencyContainer()->createAttributeManager();

        return $attributeManager->hasAttributeType($attributeType);
    }

    /**
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @return int
     * @throws AttributeTypeExistsException
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null)
    {
        $attributeManager = $this->getDependencyContainer()->createAttributeManager();

        return $attributeManager->createAttributeType($name, $inputType, $fkParentAttributeType);
    }

    /**
     * @param string $attributeName
     * @param string $attributeType
     * @param bool $isEditable
     *
     * @return int
     * @throws AttributeExistsException
     * @throws MissingAttributeTypeException
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true)
    {
        $attributeManager = $this->getDependencyContainer()->createAttributeManager();

        return $attributeManager->createAttribute($attributeName, $attributeType, $isEditable);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku)
    {
        $productManager = $this->getDependencyContainer()->createProductManager();

        return $productManager->hasAbstractProduct($sku);
    }

    /**
     * @param string $sku

     * @return int
     * @throws AbstractProductExistsException
     */
    public function createAbstractProduct($sku)
    {
        $productManager = $this->getDependencyContainer()->createProductManager();

        return $productManager->createAbstractProduct($sku);
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
        $productManager = $this->getDependencyContainer()->createProductManager();

        return $productManager->createAbstractProductAttributes($idAbstractProduct, $locale, $name, $attributes);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku)
    {
        $productManager = $this->getDependencyContainer()->createProductManager();

        return $productManager->hasConcreteProduct($sku);
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
        $productManager = $this->getDependencyContainer()->createProductManager();

        return $productManager->createConcreteProduct($sku, $idAbstractProduct, $isActive);
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
        $productManager = $this->getDependencyContainer()->createProductManager();

        return $productManager->createConcreteProductAttributes($idConcreteProduct, $locale, $name, $attributes);
    }

    /**
     * @param int $idConcreteProduct
     */
    public function touchProductActive($idConcreteProduct)
    {
        $productManager = $this->getDependencyContainer()->createProductManager();

        $productManager->touchProductActive($idConcreteProduct);
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
        return $this->getDependencyContainer()->createProductManager()->createProductUrl($sku, $url, $locale);
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
        return $this->getDependencyContainer()->createProductManager()->createAndTouchProductUrl($sku, $url, $locale);
    }

    /**
     * @param MessengerInterface $messenger
     */
    public function install(MessengerInterface $messenger = null)
    {
        $this->getDependencyContainer()->createInstaller($messenger)->install();
    }

    /**
     * @param string $sku
     *
     * @return string
     * @todo examine this as the product sku mapper is gone
     */
    public function getAbstractSkuFromConcreteProduct($sku)
    {
        return $this->getDependencyContainer()->getProductSkuMapper()->getAbstractSkuFromConcreteProduct($sku);
    }
}
