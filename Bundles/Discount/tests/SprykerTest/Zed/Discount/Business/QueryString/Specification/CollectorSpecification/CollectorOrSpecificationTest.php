<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Specification\CollectorSpecification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Specification
 * @group CollectorSpecification
 * @group CollectorOrSpecificationTest
 * Add your own group annotations below this line
 */
class CollectorOrSpecificationTest extends Unit
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
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $leftMock
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $rightMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification
     */
    protected function createCollectorOrSpecification(CollectorSpecificationInterface $leftMock, CollectorSpecificationInterface $rightMock)
    {
        return new CollectorOrSpecification($leftMock, $rightMock);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     */
    protected function createCollectorSpecificationMock()
    {
        return $this->getMockBuilder(CollectorSpecificationInterface::class)->getMock();
    }
}
