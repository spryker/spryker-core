<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Business\Model\BulkTouch\Filter;

use Codeception\Test\Unit;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterInsert;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Touch
 * @group Business
 * @group Model
 * @group BulkTouch
 * @group Filter
 * @group IdFilterInsertTest
 * Add your own group annotations below this line
 */
class IdFilterInsertTest extends Unit
{
    public const ITEM_EVENT_ACTIVE = 'active';

    /**
     * @var \Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterInsert|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $idFilterInsert;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->idFilterInsert = $this->getMockBuilder(IdFilterInsert::class)
            ->setMethods(['getIdCollection'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testFilter()
    {
        $ids = range(1, 200);

        $this->idFilterInsert->expects($this->once())
            ->method('getIdCollection')
            ->willReturn($ids);

        $result = $this->idFilterInsert->filter($ids, 'foo');

        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testFilterChunkedAllInDatabase()
    {
        $countAboveChunkSize = 500;

        $this->assertTrue(IdFilterInsert::CHUNK_SIZE < $countAboveChunkSize);
        $ids = range(1, $countAboveChunkSize);
        $itemIdChunks = array_chunk($ids, IdFilterInsert::CHUNK_SIZE);

        foreach ($itemIdChunks as $key => $itemIdChunk) {
            $this->idFilterInsert->expects($this->at($key))
                ->method('getIdCollection')
                ->willReturn($itemIdChunk);
        }

        $result = $this->idFilterInsert->filter($ids, 'foo');
        $this->assertSame([], $result);
    }

    /**
     * @return void
     */
    public function testFilterChunkedNoneInDatabase()
    {
        $countAboveChunkSize = 500;

        $ids = range(1, $countAboveChunkSize);
        $itemIdChunks = array_chunk($ids, IdFilterInsert::CHUNK_SIZE);

        foreach ($itemIdChunks as $key => $itemIdChunk) {
            $this->idFilterInsert->expects($this->at($key))
                ->method('getIdCollection')
                ->willReturn([]);
        }

        $result = $this->idFilterInsert->filter($ids, 'foo');
        $this->assertSame($ids, $result);
    }
}
