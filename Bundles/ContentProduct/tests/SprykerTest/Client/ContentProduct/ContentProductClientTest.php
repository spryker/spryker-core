<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ContentStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentProduct\ContentProductClient;
use Spryker\Client\ContentProduct\ContentProductDependencyProvider;
use Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface;
use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Shared\ContentProduct\ContentProductConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ContentStorage
 * @group ContentProductClientTest
 * Add your own group annotations below this line
 */
class ContentProductClientTest extends Unit
{
    /**
     * @var int
     */
    public const ID_CONTENT_ITEM = 1;

    /**
     * @var int
     */
    public const ID_PRODUCT_ABSTRACT = 1;

    /**
     * @var string
     */
    public const WRONG_TERM = 'TERM';

    /**
     * @var string
     */
    public const LOCALE = 'zh-CN';

    /**
     * @var \SprykerTest\Client\ContentProduct\ContentProductClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindContentProductValidTransfer()
    {
        // Arrange
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->getIdContent(static::ID_CONTENT_ITEM);
        $contentTypeContextTransfer->setTerm(ContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST);
        $contentTypeContextTransfer->getParameters(['id_product_abstracts' => [static::ID_PRODUCT_ABSTRACT]]);

        $this->setProductStorageClientReturn($contentTypeContextTransfer);

        // Act
        $systemUnderTest = $this->createContentProductClient()
            ->getContentProductAbstractListType(static::ID_CONTENT_ITEM, static::LOCALE);

        // Assert
        $this->assertEquals(ContentProductAbstractListTypeTransfer::class, get_class($systemUnderTest));
    }

    /**
     * @return void
     */
    public function testFindContentItemWithWrongTerm()
    {
        // Arrange
        $contentTypeContextTransfer = new ContentTypeContextTransfer();
        $contentTypeContextTransfer->getIdContent(static::ID_CONTENT_ITEM);
        $contentTypeContextTransfer->setTerm(static::WRONG_TERM);
        $contentTypeContextTransfer->getParameters(['id_product_abstracts' => [static::ID_PRODUCT_ABSTRACT]]);

        $this->setProductStorageClientReturn($contentTypeContextTransfer);

        // Assert
        $this->expectException(InvalidProductAbstractListTypeException::class);

        // Act
        $systemUnderTest = $this->createContentProductClient()
            ->getContentProductAbstractListType(static::ID_CONTENT_ITEM, static::LOCALE);
    }

    /**
     * @return void
     */
    public function testFindNotExistingContentProduct()
    {
        $this->setProductStorageClientReturn(null);

        // Act
        $systemUnderTest = $this->createContentProductClient()
            ->getContentProductAbstractListType(static::ID_CONTENT_ITEM, static::LOCALE);

        // Assert
        $this->assertNull($systemUnderTest);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer|null $contentTypeContextTransfer
     *
     * @return void
     */
    protected function setProductStorageClientReturn(?ContentTypeContextTransfer $contentTypeContextTransfer)
    {
        $contentProductToContentStorageClientBridge = $this->getMockBuilder(ContentProductToContentStorageClientInterface::class)->getMock();
        $contentProductToContentStorageClientBridge->method('findContentTypeContext')->willReturn($contentTypeContextTransfer);
        $this->tester->setDependency(ContentProductDependencyProvider::CLIENT_CONTENT_STORAGE, $contentProductToContentStorageClientBridge);
    }

    /**
     * @return \Spryker\Client\ContentProduct\ContentProductClientInterface
     */
    protected function createContentProductClient()
    {
        return new ContentProductClient();
    }
}
