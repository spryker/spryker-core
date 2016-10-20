<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface;
use Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapper;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductAbstractManager implements ProductAbstractManagerInterface
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
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface
     */
    protected $productAbstractAssertion;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManagerInterface
     */
    protected $pluginAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected $skuGenerator;

    /**
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeManagerInterface $attributeManager
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface $productAbstractAssertion
     * @param \Spryker\Zed\Product\Business\Product\Plugin\PluginAbstractManagerInterface $pluginAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface $skuGenerator
     */
    public function __construct(
        AttributeManagerInterface $attributeManager,
        ProductQueryContainerInterface $productQueryContainer,
        ProductToTouchInterface $touchFacade,
        ProductToUrlInterface $urlFacade,
        ProductToLocaleInterface $localeFacade,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductAbstractAssertionInterface $productAbstractAssertion,
        PluginAbstractManagerInterface $pluginAbstractManager,
        SkuGeneratorInterface $skuGenerator
    ) {
        $this->attributeManager = $attributeManager;
        $this->productQueryContainer = $productQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
        $this->localeFacade = $localeFacade;
        $this->productConcreteManager = $productConcreteManager;
        $this->productAbstractAssertion = $productAbstractAssertion;
        $this->pluginAbstractManager = $pluginAbstractManager;
        $this->skuGenerator = $skuGenerator;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->productQueryContainer
            ->queryProductAbstractBySku($sku)
            ->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productAbstractTransfer->setSku(
            $this->skuGenerator->generateProductAbstractSku($productAbstractTransfer)
        );

        $this->productAbstractAssertion->assertSkuIsUnique($productAbstractTransfer->getSku());

        $productAbstractTransfer = $this->pluginAbstractManager->triggerBeforeCreatePlugins($productAbstractTransfer);

        $productAbstractEntity = $this->persistEntity($productAbstractTransfer);

        $idProductAbstract = $productAbstractEntity->getPrimaryKey();
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->attributeManager->persistProductAbstractLocalizedAttributes($productAbstractTransfer);

        $this->pluginAbstractManager->triggerAfterCreatePlugins($productAbstractTransfer);

        $this->productQueryContainer->getConnection()->commit();

        return $idProductAbstract;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $idProductAbstract = (int)$productAbstractTransfer
            ->requireIdProductAbstract()
            ->getIdProductAbstract();

        $this->productAbstractAssertion->assertProductExists($idProductAbstract);
        $this->productAbstractAssertion->assertSkuIsUniqueWhenUpdatingProduct($idProductAbstract, $productAbstractTransfer->getSku());

        $productAbstractTransfer = $this->pluginAbstractManager->triggerBeforeUpdatePlugins($productAbstractTransfer);

        $this->persistEntity($productAbstractTransfer);
        $this->attributeManager->persistProductAbstractLocalizedAttributes($productAbstractTransfer);

        $this->pluginAbstractManager->triggerAfterUpdatePlugins($productAbstractTransfer);

        $this->productQueryContainer->getConnection()->commit();

        return $idProductAbstract;
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

        $transferGenerator = new ProductTransferMapper(); //TODO inject
        $productAbstractTransfer = $transferGenerator->convertProductAbstract($productAbstractEntity);
        $productAbstractTransfer = $this->loadLocalizedAttributes($productAbstractTransfer);

        $this->pluginAbstractManager->triggerReadPlugins($productAbstractTransfer);

        return $productAbstractTransfer;
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
            throw new MissingProductException(
                sprintf(
                    'Tried to retrieve a product concrete with sku %s, but it does not exist.',
                    $sku
                )
            );
        }

        return $productConcrete->getSpyProductAbstract()->getSku();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->attributeManager->getProductNameFromLocalizedAttributes(
            (array)$productAbstractTransfer->getLocalizedAttributes(),
            $localeTransfer,
            $productAbstractTransfer->getSku()
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchFacade->touchActive(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchActive(ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchFacade->touchInactive(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchInactive(ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $this->touchFacade->touchDeleted(ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->touchFacade->touchDeleted(ProductConstants::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productQueryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function persistEntity(ProductAbstractTransfer $productAbstractTransfer)
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
            ->setSku($productAbstractTransfer->getSku());

        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function loadLocalizedAttributes(ProductAbstractTransfer $productAbstractTransfer)
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

}
