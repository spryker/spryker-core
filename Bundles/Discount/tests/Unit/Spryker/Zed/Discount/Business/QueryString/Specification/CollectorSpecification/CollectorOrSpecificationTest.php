<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;

class CollectorOrSpecificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCollectShouldMergedUniqueDataFromBothCollections()
    {
        $leftMock = $this->createCollectorSpecificationMock();

        $items[] = new DiscountableItemTransfer();
        $items[] = new DiscountableItemTransfer();

        $leftMock->expects($this->once())
            ->method('collect')
            ->willReturn($items);

        $rightMock = $this->createCollectorSpecificationMock();

        $items[] = new DiscountableItemTransfer();
        $items[] = new DiscountableItemTransfer();
        $items[] = new DiscountableItemTransfer();

        $rightMock->expects($this->once())
            ->method('collect')
            ->willReturn($items);

        $collectorOrSpecification = $this->createCollectorOrSpecification($leftMock, $rightMock);
        $collected = $collectorOrSpecification->collect(new QuoteTransfer());

        $this->assertCount(5, $collected);
    }

    /**
     * @param $leftMock
     * @param $rightMock
     *
     * @return CollectorOrSpecification
     */
    protected function createCollectorOrSpecification($leftMock, $rightMock)
    {
        return new CollectorOrSpecification($leftMock, $rightMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectorSpecificationInterface
     */
    protected function createCollectorSpecificationMock()
    {
        return $this->getMock(CollectorSpecificationInterface::class);
    }
}
