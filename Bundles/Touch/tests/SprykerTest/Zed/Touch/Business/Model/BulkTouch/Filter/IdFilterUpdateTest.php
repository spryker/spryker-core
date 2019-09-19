<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Touch\Business\Model\BulkTouch\Filter;

use Codeception\Test\Unit;
use Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterUpdate;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

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
 * @group IdFilterUpdateTest
 * Add your own group annotations below this line
 */
class IdFilterUpdateTest extends Unit
{
    public const ITEM_EVENT_ACTIVE = 'active';

    /**
     * @var \Spryker\Zed\Touch\Business\Model\BulkTouch\Filter\IdFilterUpdate|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $idFilterUpdate;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->touchQueryContainer = $this->getMockBuilder(TouchQueryContainerInterface::class)->getMock();

        $this->idFilterUpdate = $this->getMockBuilder(IdFilterUpdate::class)
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

        $this->idFilterUpdate->expects($this->once())
            ->method('getIdCollection')
            ->willReturn($ids);

        $result = $this->idFilterUpdate->filter($ids, 'foo');
        $this->assertSame($ids, $result);
    }

    /**
     * @return void
     */
    public function testFilterChunkedAllInDatabase()
    {
        $countAboveChunkSize = 500;
        $this->assertTrue(IdFilterUpdate::CHUNK_SIZE < $countAboveChunkSize);
        $ids = range(1, $countAboveChunkSize);

        $this->idFilterUpdate->expects($this->atLeastOnce())
            ->method('getIdCollection')
            ->willReturn(range(1, IdFilterUpdate::CHUNK_SIZE));

        $result = $this->idFilterUpdate->filter($ids, 'foo');
        $this->assertCount($countAboveChunkSize, $result);
    }

    /**
     * @return void
     */
    public function testFilterChunkedNoneInDatabase()
    {
        $countAboveChunkSize = 500;
        $ids = range(1, $countAboveChunkSize);

        $this->idFilterUpdate->expects($this->atLeastOnce())
            ->method('getIdCollection')
            ->willReturn([]);

        $result = $this->idFilterUpdate->filter($ids, 'foo');
        $this->assertSame([], $result);
    }
}
