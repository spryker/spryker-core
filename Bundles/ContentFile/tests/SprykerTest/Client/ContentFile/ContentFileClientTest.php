<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ContentFile;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentFileListTypeTransfer;
use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Spryker\Client\ContentFile\ContentFileDependencyProvider;
use Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientInterface;
use Spryker\Client\ContentFile\Exception\InvalidFileListTermException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ContentFile
 * @group ContentFileClientTest
 * Add your own group annotations below this line
 */
class ContentFileClientTest extends Unit
{
    protected const LOCALE_NAME = 'de_DE';
    protected const WRONG_KEY_CONTENT = 'fl-0';
    protected const EXCEPTION_ERROR_MESSAGE = 'There is no matching Term for FileListType when provided with term %s';

    /**
     * @var \SprykerTest\Client\ContentFile\ContentFileClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteFileListTypeByKeyNotFound(): void
    {
        // Act
        $contentFileListTypeTransfer = $this->tester->getClient()
            ->executeFileListTypeByKey(static::WRONG_KEY_CONTENT, static::LOCALE_NAME);

        // Assert
        $this->assertNull($contentFileListTypeTransfer);
    }

    /**
     * @return void
     */
    public function testExecuteFileListTypeByKeyInvalidFileListType(): void
    {
        // Arrange
        $content = (new ContentTypeContextTransfer())
            ->setIdContent('1')
            ->setKey('br-1')
            ->setTerm('Banner')
            ->setParameters([]);

        $this->setStorageMock($content);

        // Assert
        $this->expectExceptionObject(
            new InvalidFileListTermException(
                sprintf(static::EXCEPTION_ERROR_MESSAGE, 'Banner')
            )
        );

        // Act
        $this->tester->getClient()
            ->executeFileListTypeByKey('br-1', static::LOCALE_NAME);
    }

    /**
     * @return void
     */
    public function testExecuteFileListTypeByKeySuccess(): void
    {
        // Arrange
        $content = (new ContentTypeContextTransfer())
            ->setIdContent('2')
            ->setKey('fl-1')
            ->setTerm('File List')
            ->setParameters([]);

        $this->setStorageMock($content);

        // Act
        $contentFileListTypeTransfer = $this->tester->getClient()
            ->executeFileListTypeByKey('fl-1', static::LOCALE_NAME);

        // Assert
        $this->assertInstanceOf(ContentFileListTypeTransfer::class, $contentFileListTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeContextTransfer $content
     *
     * @return void
     */
    protected function setStorageMock(ContentTypeContextTransfer $content): void
    {
        $contentFileToContentStorageClientBridge = $this->getMockBuilder(ContentFileToContentStorageClientInterface::class)->getMock();
        $contentFileToContentStorageClientBridge->method('findContentTypeContextByKey')->willReturn($content);
        $this->tester->setDependency(ContentFileDependencyProvider::CLIENT_CONTENT_STORAGE, $contentFileToContentStorageClientBridge);
    }
}
