<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelFilterCriteriaTest
 * Add your own group annotations below this line
 */
class PropelFilterCriteriaTest extends Unit
{
    /**
     * @return void
     */
    public function testToCriteriaShouldReturnEmptyCriteriaWhenNothingWasSet()
    {
        $filterTransfer = new FilterTransfer();

        $filterCriteria = new PropelFilterCriteria($filterTransfer);
        $propelCriteria = $filterCriteria->toCriteria();

        $this->assertInstanceOf(Criteria::class, $propelCriteria);
        $this->assertEquals(-1, $propelCriteria->getLimit());
        $this->assertEquals(0, $propelCriteria->getOffset());
        $this->assertEquals([], $propelCriteria->getOrderByColumns());
    }

    /**
     * @return void
     */
    public function testToCriteriaShouldReturnCriteriaWithParameters()
    {
        $filterTransfer = new FilterTransfer();
        $filterTransfer->setLimit(10);
        $filterTransfer->setOffset(0);
        $filterTransfer->setOrderDirection('DESC');
        $filterTransfer->setOrderBy('foobar');

        $filterCriteria = new PropelFilterCriteria($filterTransfer);
        $propelCriteria = $filterCriteria->toCriteria();

        $this->assertInstanceOf(Criteria::class, $propelCriteria);
        $this->assertEquals(10, $propelCriteria->getLimit());
        $this->assertEquals(0, $propelCriteria->getOffset());
        $this->assertEquals(['foobar DESC'], $propelCriteria->getOrderByColumns());
    }
}
