<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\Install;

use Elastica\Request;
use Generated\Shared\Transfer\IndexDefinitionTransfer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Spryker\SearchElasticsearch\tests\SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index\AbstractIndexTest;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Install\IndexInstaller;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group Install
 * @group IndexInstallerTest
 * Add your own group annotations below this line
 */
class IndexInstallerTest extends AbstractIndexTest
{
    /**
     * @return void
     */
    public function testCanSendRequestToElasticsearchOnIndexCreation(): void
    {
        $fixtureMappings = ['foo' => 'bar'];
        $mappingBuilder = $this->createMappingBuilderMock($fixtureMappings);
        $indexMock = $this->createIndexMock();
        $indexMock->expects($this->once())->method('request')->with(
            '',
            Request::PUT,
            [
                'mappings' => $fixtureMappings,
            ]
        );
        $clientMock = $this->createClientMock($indexMock);

        $indexInstaller = new IndexInstaller(
            $clientMock,
            $mappingBuilder
        );

        $indexDefinitionTransfer = new IndexDefinitionTransfer();
        $indexDefinitionTransfer->setMappings([[]]);

        $indexInstaller->run($indexDefinitionTransfer, new NullLogger());
    }

    /**
     * @return void
     */
    public function testLoggsInstallationMessage(): void
    {
        $indexName = 'index-name';
        $mappingBuilder = $this->createMappingBuilderMock();
        $indexMock = $this->createIndexMock();
        $indexMock->method('getName')->willReturn($indexName);
        $clientMock = $this->createClientMock($indexMock);

        /** @var \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject $loggerMock */
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('info')->with(
            sprintf('Import mappings and settings for index "%s".', $indexName)
        );

        $indexInstaller = new IndexInstaller(
            $clientMock,
            $mappingBuilder
        );

        $indexInstaller->run(new IndexDefinitionTransfer(), $loggerMock);
    }

    /**
     * @dataProvider isAcceptedWhenIndexExistsProvider
     *
     * @param bool $expectedIsAccepted
     * @param bool $isIndexExists
     *
     * @return void
     */
    public function testIsAcceptedWhenIndexNotExists(bool $expectedIsAccepted, bool $isIndexExists): void
    {
        $indexMock = $this->createIndexMock();
        $indexMock->method('exists')->willReturn($isIndexExists);
        $clientMock = $this->createClientMock($indexMock);

        $indexInstaller = new IndexInstaller(
            $clientMock,
            $this->createMappingBuilderMock()
        );

        $isAccepted = $indexInstaller->accept(new IndexDefinitionTransfer());

        $this->assertEquals($expectedIsAccepted, $isAccepted);
    }

    /**
     * @return array
     */
    public function isAcceptedWhenIndexExistsProvider(): array
    {
        return [
            'index exists' => [false, true],
            'index does not exist' => [true, false],
        ];
    }
}
