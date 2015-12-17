<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Business;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Product\Business\Exception\AttributeExistsException;
use Spryker\Zed\Product\Business\Exception\AttributeTypeExistsException;
use Spryker\Zed\Product\Business\Exception\MissingAttributeTypeException;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Model\ProductBatchResult;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @method ProductBusinessFactory getFactory()
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
        return $this->getFactory()
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
        return $this->getFactory()
            ->createHttpFileImporter()
            ->receiveUploadedFile($uploadedFilename);
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
        return $this->getFactory()->createProductManager()->getAbstractProductIdBySku($sku);
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
        return $this->getFactory()->createProductManager()->getConcreteProductIdBySku($sku);
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
        return $this->getFactory()->createProductManager()->getAbstractProductIdByConcreteSku($sku);
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
        return $this->getFactory()->createProductManager()->getEffectiveTaxRateForConcreteProduct($sku);
    }

    /**
     * @param string $attributeName
     *
     * @return bool
     */
    public function hasAttribute($attributeName)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->hasAttribute($attributeName);
    }

    /**
     * @param string $attributeType
     *
     * @return bool
     */
    public function hasAttributeType($attributeType)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->hasAttributeType($attributeType);
    }

    /**
     * @param string $name
     * @param string $inputType
     * @param int|null $fkParentAttributeType
     *
     * @throws AttributeTypeExistsException
     *
     * @return int
     */
    public function createAttributeType($name, $inputType, $fkParentAttributeType = null)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->createAttributeType($name, $inputType, $fkParentAttributeType);
    }

    /**
     * @param string $attributeName
     * @param string $attributeType
     * @param bool $isEditable
     *
     * @throws AttributeExistsException
     * @throws MissingAttributeTypeException
     *
     * @return int
     */
    public function createAttribute($attributeName, $attributeType, $isEditable = true)
    {
        $attributeManager = $this->getFactory()->createAttributeManager();

        return $attributeManager->createAttribute($attributeName, $attributeType, $isEditable);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku)
    {
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->hasAbstractProduct($sku);
    }

    /**
     * @param AbstractProductTransfer $abstractProductTransfer
     *
     * @return int
     */
    public function createAbstractProduct(AbstractProductTransfer $abstractProductTransfer)
    {
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->createAbstractProduct($abstractProductTransfer);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku)
    {
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->hasConcreteProduct($sku);
    }

    /**
     * @param ConcreteProductTransfer $concreteProductTransfer
     * @param int $idAbstractProduct
     *
     * @return int
     */
    public function createConcreteProduct(ConcreteProductTransfer $concreteProductTransfer, $idAbstractProduct)
    {
        $productManager = $this->getFactory()->createProductManager();

        return $productManager->createConcreteProduct($concreteProductTransfer, $idAbstractProduct);
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    public function touchProductActive($idAbstractProduct)
    {
        $productManager = $this->getFactory()->createProductManager();

        $productManager->touchProductActive($idAbstractProduct);
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
        return $this->getFactory()->createProductManager()->createProductUrl($sku, $url, $locale);
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
        return $this->getFactory()->createProductManager()->createAndTouchProductUrl($sku, $url, $locale);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger = null)
    {
        $this->getFactory()->createInstaller($messenger)->install();
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuFromConcreteProduct($sku)
    {
        return $this->getFactory()->createProductManager()->getAbstractSkuFromConcreteProduct($sku);
    }

    /**
     * @param string $concreteSku
     *
     * @return ConcreteProductTransfer
     */
    public function getConcreteProduct($concreteSku)
    {
        return $this->getFactory()->createProductManager()->getConcreteProduct($concreteSku);
    }

}
