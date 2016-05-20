<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Category\Business\Foo;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryLocalizedTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Category\Business\Foo\CategoryManager;
use Spryker\Zed\Category\Business\Tree\ClosureTableWriter;
use Spryker\Zed\Category\Business\Tree\NodeWriter;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleBridge;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryBridge;
use Unit\Spryker\Zed\Category\Business\Foo\Fixtures\Input\CategoryManagerInput;

/**
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Business
 * @group CategoryTreeFormatter
 */
class FooTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Unit\Spryker\Zed\Category\Business\Foo\Fixtures\Input\CategoryManagerInput
     */
    protected $input;

    /**
     * @var \Spryker\Zed\Category\Business\Foo\CategoryManager
     */
    protected $categoryManager;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeDE;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeEN;

    /**
     * @return void
     */
    protected function setUp()
    {
        $localeFacade = new LocaleFacade();
        $localeFacade = new CategoryToLocaleBridge($localeFacade);
        $categoryFacade = new CategoryFacade();
        $productCategoryFacade = new ProductCategoryToCategoryBridge($categoryFacade);
        $categoryQueryContainer = new CategoryQueryContainer();
        $nodeWriter = new NodeWriter($categoryQueryContainer);
        $closureTableWriter = new ClosureTableWriter($categoryQueryContainer);

        $this->categoryManager = new CategoryManager(
            $productCategoryFacade,
            $localeFacade,
            $categoryQueryContainer,
            $nodeWriter,
            $closureTableWriter
        );

        $this->localeDE = $localeFacade->getLocale('de_DE');
        $this->localeEN = $localeFacade->getLocale('en_US');

        $this->input = new CategoryManagerInput();
    }

    public function test_create_should_add_category_node_with_url_and_attributes()
    {
        $CategoryLocalizedTransfer = (new CategoryLocalizedTransfer())->fromArray(
            $this->input->getCategoryData()['de_DE']
        );
        $CategoryLocalizedTransfer->setLocale($this->localeDE);


        $CategoryLocalizedTransfer = $this->categoryManager->create($CategoryLocalizedTransfer);

        $this->assertInstanceOf(CategoryLocalizedTransfer::class, $CategoryLocalizedTransfer);
        $this->assertEquals($CategoryLocalizedTransfer->getName(), 'Foo DE');
        $this->assertEquals($CategoryLocalizedTransfer->getUrl(), '/de/foo-de');
    }

    public function SKIP_test_create_with_multiple_locales_should_add_category_node_with_localized_urls_and_attributes()
    {
        //DE
        $CategoryLocalizedTransfer = (new CategoryLocalizedTransfer())->fromArray(
            $this->input->getCategoryData()
        );

        $CategoryLocalizedTransfer = $this->categoryManager->create($CategoryLocalizedTransfer, $this->localeDE);

        $this->assertInstanceOf(CategoryLocalizedTransfer::class, $CategoryLocalizedTransfer);
        $this->assertEquals($CategoryLocalizedTransfer->getName(), 'Foo DE');
        $this->assertEquals($CategoryLocalizedTransfer->getUrl(), '/de/foo-de');

        //EN
        $CategoryLocalizedTransfer = (new CategoryLocalizedTransfer())->fromArray(
            $this->input->getCategoryData()
        );

        $CategoryLocalizedTransfer = $this->categoryManager->create($CategoryLocalizedTransfer, $this->localeEN);

        $this->assertInstanceOf(CategoryLocalizedTransfer::class, $CategoryLocalizedTransfer);
        $this->assertEquals($CategoryLocalizedTransfer->getName(), 'Foo EN');
        $this->assertEquals($CategoryLocalizedTransfer->getUrl(), '/de/foo-en');
    }

    /**
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer($localeName, $data)
    {
        $localeName = Store::getInstance()->getCurrentLocale();
        $localeTransfer = $this->localeFacade->getLocale($localeName);

        $localizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
        $localizedAttributesTransfer
            ->setLocale($localeTransfer)
            ->setName('Foo');

        return $localizedAttributesTransfer;
    }

}
