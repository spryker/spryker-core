<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Propel;

use Generated\Shared\Transfer\FilterTransfer;
use SprykerEngine\Zed\Propel\PropelFilterCriteria;

class PropelFilterCriteriaTest extends \PHPUnit_Framework_TestCase
{

    public function testToCriteriaShouldReturnEmptyCriteriaWhenNothingWasSet()
    {
        $filterTransfer = new FilterTransfer();

        $filterCriteria = new PropelFilterCriteria($filterTransfer);
        $propelCriteria = $filterCriteria->toCriteria();

        $this->assertInstanceOf('Propel\Runtime\ActiveQuery\Criteria', $propelCriteria);
        $this->assertEquals(-1, $propelCriteria->getLimit());
        $this->assertEquals(0, $propelCriteria->getOffset());
        $this->assertEquals([], $propelCriteria->getOrderByColumns());
    }

    public function testToCriteriaShouldReturnCriteriaWithParameters()
    {
        $filterTransfer = new FilterTransfer();
        $filterTransfer->setLimit(10);
        $filterTransfer->setOffset(0);
        $filterTransfer->setOrderDirection('DESC');
        $filterTransfer->setOrderBy('foobar');

        $filterCriteria = new PropelFilterCriteria($filterTransfer);
        $propelCriteria = $filterCriteria->toCriteria();

        $this->assertInstanceOf('Propel\Runtime\ActiveQuery\Criteria', $propelCriteria);
        $this->assertEquals(10, $propelCriteria->getLimit());
        $this->assertEquals(0, $propelCriteria->getOffset());
        $this->assertEquals(['foobar DESC'], $propelCriteria->getOrderByColumns());
    }

}
