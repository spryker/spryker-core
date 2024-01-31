<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\CategoryUrlCollectionRequestBuilder;
use Generated\Shared\DataBuilder\NodeBuilder;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\Category\CategoryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group CreateCategoryUrlCollectionTest
 * Add your own group annotations below this line
 */
class CreateCategoryUrlCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CATEGORY_NAME = 'Test Category Name';

    /**
     * @var string
     */
    protected const TEST_CATEGORY_URL_ASSERT_PATTERN = '/.*\/test-category-name/';

    /**
     * @uses \Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl\CategoryNodeExistsCategoryUrlValidatorRule::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND = 'category.validation.category_node_entity_not_found';

    /**
     * @uses \Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl\CategoryLocalizedAttributeExistsCategoryUrlValidatorRule::GLOSSARY_KEY_VALIDATION_CATEGORY_ATTRIBUTE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_ATTRIBUTE_ENTITY_NOT_FOUND = 'category.validation.category_attribute_entity_not_found';

    /**
     * @uses \Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl\CategoryClosureTableExistsCategoryUrlValidatorRule::GLOSSARY_KEY_VALIDATION_CATEGORY_CLOSURE_TABLE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_CLOSURE_TABLE_ENTITY_NOT_FOUND = 'category.validation.category_closure_table_entity_not_found';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected CategoryBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatesCategoryUrl(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());
        $this->tester->haveCategoryClosureTableForCategoryNode($nodeTransfer);

        $localeTransfer = $this->tester->haveLocale();
        $categoryLocalizedAttributesTransfer = $this->tester->createCategoryLocalizedAttributesForLocale(
            $localeTransfer,
            $categoryTransfer->getIdCategoryOrFail(),
            [CategoryLocalizedAttributesTransfer::NAME => static::TEST_CATEGORY_NAME],
        );

        $categoryTransfer
            ->setCategoryNode($nodeTransfer)
            ->addLocalizedAttributes($categoryLocalizedAttributesTransfer->setLocale($localeTransfer));

        $categoryUrlCollectionRequestTransfer = (new CategoryUrlCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addCategory($categoryTransfer);

        // Act
        $categoryUrlCollectionResponseTransfer = $this->tester->getFacade()->createCategoryUrlCollection(
            $categoryUrlCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $categoryUrlCollectionResponseTransfer->getErrors());
        $categoryUrlEntity = $this->tester->findUrlCategoryEntityByNodeAndLocale($nodeTransfer, $localeTransfer);
        $this->assertNotNull($categoryUrlEntity);
        $this->assertRegExp(static::TEST_CATEGORY_URL_ASSERT_PATTERN, $categoryUrlEntity->getUrl());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCategoryNodeDoesNotExist(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = (new NodeBuilder([NodeTransfer::ID_CATEGORY_NODE => 0]))->build();

        $localeTransfer = $this->tester->haveLocale();
        $categoryLocalizedAttributesTransfer = $this->tester->createCategoryLocalizedAttributesForLocale(
            $localeTransfer,
            $categoryTransfer->getIdCategoryOrFail(),
            [CategoryLocalizedAttributesTransfer::NAME => static::TEST_CATEGORY_NAME],
        );

        $categoryTransfer
            ->setCategoryNode($nodeTransfer)
            ->addLocalizedAttributes($categoryLocalizedAttributesTransfer->setLocale($localeTransfer));

        $categoryUrlCollectionRequestTransfer = (new CategoryUrlCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addCategory($categoryTransfer);

        // Act
        $categoryUrlCollectionResponseTransfer = $this->tester->getFacade()->createCategoryUrlCollection(
            $categoryUrlCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(2, $categoryUrlCollectionResponseTransfer->getErrors());

        $errorTransfer = $categoryUrlCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND, $errorTransfer->getMessage());
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertNull($this->tester->findUrlCategoryEntityByNodeAndLocale($nodeTransfer, $localeTransfer));
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCategoryAttributeDoesNotExist(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());
        $this->tester->haveCategoryClosureTableForCategoryNode($nodeTransfer);

        $localeTransfer = $this->tester->haveLocale();
        $categoryLocalizedAttributesTransfer = (new CategoryLocalizedAttributesBuilder([
            CategoryLocalizedAttributesTransfer::LOCALE => $localeTransfer->toArray(),
        ]))->build();

        $categoryTransfer
            ->setCategoryNode($nodeTransfer)
            ->addLocalizedAttributes($categoryLocalizedAttributesTransfer->setLocale($localeTransfer));

        $categoryUrlCollectionRequestTransfer = (new CategoryUrlCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addCategory($categoryTransfer);

        // Act
        $categoryUrlCollectionResponseTransfer = $this->tester->getFacade()->createCategoryUrlCollection(
            $categoryUrlCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $categoryUrlCollectionResponseTransfer->getErrors());

        $errorTransfer = $categoryUrlCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_CATEGORY_ATTRIBUTE_ENTITY_NOT_FOUND, $errorTransfer->getMessage());
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertNull($this->tester->findUrlCategoryEntityByNodeAndLocale($nodeTransfer, $localeTransfer));
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCategoryClosureTableDoesNotExist(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());

        $localeTransfer = $this->tester->haveLocale();
        $categoryLocalizedAttributesTransfer = $this->tester->createCategoryLocalizedAttributesForLocale(
            $localeTransfer,
            $categoryTransfer->getIdCategoryOrFail(),
            [CategoryLocalizedAttributesTransfer::NAME => static::TEST_CATEGORY_NAME],
        );

        $categoryTransfer
            ->setCategoryNode($nodeTransfer)
            ->addLocalizedAttributes($categoryLocalizedAttributesTransfer->setLocale($localeTransfer));

        $categoryUrlCollectionRequestTransfer = (new CategoryUrlCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addCategory($categoryTransfer);

        // Act
        $categoryUrlCollectionResponseTransfer = $this->tester->getFacade()->createCategoryUrlCollection(
            $categoryUrlCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $categoryUrlCollectionResponseTransfer->getErrors());

        $errorTransfer = $categoryUrlCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_CATEGORY_CLOSURE_TABLE_ENTITY_NOT_FOUND, $errorTransfer->getMessage());
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertNull($this->tester->findUrlCategoryEntityByNodeAndLocale($nodeTransfer, $localeTransfer));
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredTransferPropertyIsMissingDataProvider
     *
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredTransferPropertyIsMissing(CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createCategoryUrlCollection($categoryUrlCollectionRequestTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer>>
     */
    protected function throwsExceptionWhenRequiredTransferPropertyIsMissingDataProvider(): array
    {
        return [
            'CategoryUrlCollectionRequestTransfer.isTransactional property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => null,
                ]))->build(),
            ],
            'CategoryUrlCollectionRequestTransfer.categories property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                    CategoryUrlCollectionRequestTransfer::CATEGORIES => [],
                ]))->build(),
            ],
            'CategoryUrlCollectionRequestTransfer.categories.categoryNode property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                ]))->withCategory([
                    CategoryTransfer::CATEGORY_NODE => null,
                ])->build(),
            ],
            'CategoryUrlCollectionRequestTransfer.categories.localizedAttribute property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                ]))->withCategory((new CategoryBuilder([
                    CategoryTransfer::LOCALIZED_ATTRIBUTES => [],
                ]))->withCategoryNode())->build(),
            ],
            'CategoryUrlCollectionRequestTransfer.categories.categoryNode.idCategoryNode property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                ]))->withCategory((new CategoryBuilder())
                    ->withLocalizedAttributes()
                    ->withCategoryNode([
                        NodeTransfer::ID_CATEGORY_NODE => null,
                    ]))
                    ->build(),
            ],
            'CategoryUrlCollectionRequestTransfer.categories.localizedAttribute.locale property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                ]))->withCategory((new CategoryBuilder())
                    ->withLocalizedAttributes([
                        CategoryLocalizedAttributesTransfer::LOCALE => null,
                    ])
                    ->withCategoryNode([
                        NodeTransfer::ID_CATEGORY_NODE => 1,
                    ]))
                    ->build(),
            ],
            'CategoryUrlCollectionRequestTransfer.categories.localizedAttribute.locale.localeName property is not set' => [
                (new CategoryUrlCollectionRequestBuilder([
                    CategoryUrlCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                ]))->withCategory((new CategoryBuilder())
                    ->withLocalizedAttributes([
                        CategoryLocalizedAttributesTransfer::LOCALE => [
                            LocaleTransfer::LOCALE_NAME => null,
                        ],
                    ])
                    ->withCategoryNode([
                        NodeTransfer::ID_CATEGORY_NODE => 1,
                    ]))
                    ->build(),
            ],
        ];
    }
}
