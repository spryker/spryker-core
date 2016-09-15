<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel;

use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group PropelFilterCriteriaTest
 */
class PropelFilterCriteriaTest extends \PHPUnit_Framework_TestCase
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
