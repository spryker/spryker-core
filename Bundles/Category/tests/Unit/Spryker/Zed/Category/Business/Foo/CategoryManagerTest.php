<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Category\Business\Foo;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Business\Foo\CategoryManager;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;

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
     * @var \Spryker\Zed\Category\Business\Foo\CategoryManager
     */
    protected $categoryManager;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

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
        $this->categoryQueryContainer = new CategoryQueryContainer();
        $this->categoryManager = new CategoryManager($this->categoryQueryContainer);

        $this->localeDE = (new LocaleTransfer())->setLocaleName('de_DE');
        $this->localeEN = (new LocaleTransfer())->setLocaleName('en_US');
    }

    /**
     * @return array
     */
    protected function getCategoryFixtureData()
    {
        return [
            'de_DE' => [
                'category_key' => 'CATEGORY_KEY',
                'is_active' => true,
                'is_in_menu' => true,
                'is_clickable' => true,
                'name' => 'Foo DE',
                'url' => '/de/foo-de',
                'meta_title' => 'foo DE title',
                'meta_keywords' => 'foo DE meta',
                'category_image_name' => 'foo DE image',
            ],
            'en_US' => [
                'category_key' => 'CATEGORY_KEY',
                'is_active' => true,
                'is_in_menu' => true,
                'is_clickable' => true,
                'name' => 'Foo EN',
                'url' => '/de/foo-en',
                'meta_title' => 'foo EN title',
                'meta_keywords' => 'foo EN meta',
                'category_image_name' => 'foo EN image',
            ]
        ];
    }

    public function testCreateCategory()
    {
        $categoryTransfer = (new CategoryTransfer())->fromArray(
            $this->getCategoryFixtureData()['de_DE']
        );

        $categoryTransfer = $this->categoryManager->create($categoryTransfer, $this->localeDE);

        $this->assertInstanceOf(CategoryTransfer::class, $categoryTransfer);
        $this->assertEquals($categoryTransfer->getName(), 'Foo DE');
        $this->assertEquals($categoryTransfer->getUrl(), '/de/foo-de');
    }

    public function testCreateCategoryWithMultipleLocales()
    {
        $categoryTransfer = (new CategoryTransfer())->fromArray(
            $this->getCategoryFixtureData()['de_DE']
        );

        $categoryTransfer = $this->categoryManager->create($categoryTransfer, $this->localeDE);

        $this->assertInstanceOf(CategoryTransfer::class, $categoryTransfer);
        $this->assertEquals($categoryTransfer->getName(), 'Foo DE');
        $this->assertEquals($categoryTransfer->getUrl(), '/de/foo-de');


        $categoryTransfer = (new CategoryTransfer())->fromArray(
            $this->getCategoryFixtureData()['en_US']
        );

        $categoryTransfer = $this->categoryManager->create($categoryTransfer, $this->localeEN);

        $this->assertInstanceOf(CategoryTransfer::class, $categoryTransfer);
        $this->assertEquals($categoryTransfer->getName(), 'Foo EN');
        $this->assertEquals($categoryTransfer->getUrl(), '/de/foo-en');
    }

}
