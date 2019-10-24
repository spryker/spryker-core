<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\Definition\Reader;

use Codeception\Test\Unit;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\SearchElasticsearch\Business\Definition\Reader\IndexDefinitionReader;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchToUtilEncodingBridge;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Definition
 * @group Reader
 * @group IndexDefinitionReaderTest
 * Add your own group annotations below this line
 */
class IndexDefinitionReaderTest extends Unit
{
    /**
     * @return void
     */
    public function testReadConvertsAJsonStringIntoAnArray(): void
    {
        $splFileInfoMock = $this->getFileMock();
        $splFileInfoMock->expects($this->once())->method('getContents')->willReturn('{"key": "value"}');

        $searchToUtilEncodingBridge = new SearchToUtilEncodingBridge(new UtilEncodingService());
        $indexDefinitionReader = new IndexDefinitionReader($searchToUtilEncodingBridge);

        $this->assertIsArray($indexDefinitionReader->read($splFileInfoMock));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Finder\SplFileInfo
     */
    protected function getFileMock()
    {
        $splFileInfoMockBuilder = $this->getMockBuilder(SplFileInfo::class)->setMethods(['getContents'])->disableOriginalConstructor();
        $splFileInfoMock = $splFileInfoMockBuilder->getMock();

        return $splFileInfoMock;
    }
}
