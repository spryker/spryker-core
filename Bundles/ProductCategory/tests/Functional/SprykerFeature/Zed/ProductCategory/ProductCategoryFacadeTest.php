<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductCategory;

use Generated\Shared\Transfer\AbstractProductTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

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

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();

        $this->localeFacade = $this->locator->locale()->facade();
        $this->productFacade = $this->locator->product()->facade();
        $this->categoryFacade = $this->locator->category()->facade();
        $this->productCategoryFacade = new ProductCategoryFacade(new Factory('ProductCategory'), $this->locator);
        $this->productCategoryFacade->setExternalDependencies(new Container());
        $this->productCategoryQueryContainer = new ProductQueryContainer(
            new PersistenceFactory('ProductCategory'),
            $this->locator
        );
    }

    /**
     * @group ProductCategory
     */
    public function testCreateAttributeTypeCreatesAndReturnsId()
    {
        $this->eraseUrlsAndCategories();
        $abstractSku = 'AnAbstractTestProduct';
        $concreteSku = 'ATestProduct';
        $categoryName = 'ATestCategory';
        $localeName = 'ABCDE';
        $abstractName = 'abstractName';
        $concreteName = 'concreteName';

        $locale = $this->localeFacade->createLocale($localeName);

        $abstractProductTransfer = new AbstractProductTransfer();
        $abstractProductTransfer->setSku($abstractSku);
        $abstractProductTransfer->setAttributes([]);
        $abstractProductTransfer->setName($abstractName);
        $localizedAttributes = new LocalizedAttributesTransfer();
        $localizedAttributes->setAttributes([]);
        $localizedAttributes->setLocale($locale);
        $abstractProductTransfer->addLocalizedAttributes($localizedAttributes);
        $idAbstractProduct = $this->productFacade->createAbstractProduct($abstractProductTransfer);

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setSku($concreteSku);
        $concreteProductTransfer->setAttributes([]);
        $concreteProductTransfer->setName($categoryName);
        $concreteProductTransfer->addLocalizedAttributes($localizedAttributes);
        $this->productFacade->createConcreteProduct($concreteProductTransfer, $idAbstractProduct);

        $categoryTransfer = new CategoryTransfer();
        $categoryTransfer->setName($categoryName);
        $idCategory = $this->categoryFacade->createCategory($categoryTransfer, $locale);

        $categoryNodeTransfer = new NodeTransfer();
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setIsRoot(true);
        $this->categoryFacade->createCategoryNode($categoryNodeTransfer, $locale);
        $this->productCategoryFacade->createProductCategoryMapping($abstractSku, $categoryName, $locale);

        $this->assertTrue(
            $this->productCategoryFacade->hasProductCategoryMapping(
                $abstractSku,
                $categoryName,
                $locale
            )
        );
    }

    protected function eraseUrlsAndCategories()
    {
        Propel::getConnection()->query('SET foreign_key_checks = 0;');
        SpyUrlQuery::create()->deleteAll();
        SpyCategoryClosureTableQuery::create()->deleteAll();
        SpyCategoryAttributeQuery::create()->deleteAll();
        SpyCategoryNodeQuery::create()->deleteAll();
        SpyCategoryQuery::create()->deleteAll();
        Propel::getConnection()->query('SET foreign_key_checks = 1;');
    }

}
