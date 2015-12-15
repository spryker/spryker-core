<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\ProductCategory;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Spryker\Zed\Kernel\AbstractFunctionalTest;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacade;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductCategory\ProductCategoryDependencyProvider;

/**
 * @group Spryker
 * @group Zed
 * @group ProductCategory
 * @group ProductCategoryFacade
 */
class ProductCategoryFacadeTest extends AbstractFunctionalTest
{

    /**
     * @var ProductCategoryFacade
     */
    protected $productCategoryFacade;

    /**
     * @var ProductCategoryQueryContainerInterface
     */
    protected $productCategoryQueryContainer;

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @var CategoryFacade
     */
    protected $categoryFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();

        $this->localeFacade = $this->locator->locale()->facade();
        $this->productFacade = $this->locator->product()->facade();
        $this->categoryFacade = $this->locator->category()->facade();
        $this->productCategoryFacade = new ProductCategoryFacade();

        $container = new Container();
        $dependencyProvider = new ProductCategoryDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);

        $this->productCategoryFacade->setExternalDependencies($container);
        $this->productCategoryQueryContainer = new ProductQueryContainer(
            new PersistenceFactory('ProductCategory'),
            $this->locator
        );
    }

    /**
     * @group ProductCategory
     *
     * @return void
     */
    public function testCreateAttributeTypeCreatesAndReturnsId()
    {
        $abstractSku = 'AnAbstractTestProduct';
        $concreteSku = 'ATestProduct';
        $categoryName = 'ATestCategory';
        $localeName = 'ABCDE';
        $abstractName = 'abstractName';
        $categoryKey = '100TEST';

        $locale = $this->localeFacade->createLocale($localeName);

        $abstractProductTransfer = new AbstractProductTransfer();
        $abstractProductTransfer->setSku($abstractSku);
        $abstractProductTransfer->setAttributes([]);
        $localizedAttributes = new LocalizedAttributesTransfer();
        $localizedAttributes->setAttributes([]);
        $localizedAttributes->setLocale($locale);
        $localizedAttributes->setName($abstractName);
        $abstractProductTransfer->addLocalizedAttributes($localizedAttributes);
        $idAbstractProduct = $this->productFacade->createAbstractProduct($abstractProductTransfer);

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setSku($concreteSku);
        $concreteProductTransfer->setAttributes([]);
        $concreteProductTransfer->addLocalizedAttributes($localizedAttributes);
        $concreteProductTransfer->setIsActive(true);
        $this->productFacade->createConcreteProduct($concreteProductTransfer, $idAbstractProduct);

        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setName($categoryName);
        $categoryTransfer->setCategoryKey($categoryKey);
        $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $locale);

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsRoot(true);
        $this->categoryFacade->createCategoryNode($categoryNodeTransfer, $locale, false);
        $this->productCategoryFacade->createProductCategoryMapping($abstractSku, $categoryName, $locale);

        $this->assertTrue(
            $this->productCategoryFacade->hasProductCategoryMapping(
                $abstractSku,
                $categoryName,
                $locale
            )
        );
    }

}
