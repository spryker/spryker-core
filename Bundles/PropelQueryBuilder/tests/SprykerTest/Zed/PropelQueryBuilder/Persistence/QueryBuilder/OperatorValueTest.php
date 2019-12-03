<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelQueryBuilder\Persistence\QueryBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\BeginsWith;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Contains;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\EndsWith;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Equal;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Greater;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\GreaterOrEqual;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\In;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\Less;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\LessOrEqual;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotBeginsWith;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotContains;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotEndsWith;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotEqual;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\NotIn;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelQueryBuilder
 * @group Persistence
 * @group QueryBuilder
 * @group OperatorValueTest
 * Add your own group annotations below this line
 */
class OperatorValueTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected $rule;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->rule = new PropelQueryBuilderRuleSetTransfer();
        $this->rule->setField('foo');
        $this->rule->setOperator('=');
        $this->rule->setValue('bar');
    }

    /**
     * @return void
     */
    public function testBeginsWith(): void
    {
        $operator = new BeginsWith();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar%', $value);
    }

    /**
     * @return void
     */
    public function testContains(): void
    {
        $operator = new Contains();
        $value = $operator->getValue($this->rule);

        $this->assertSame('%bar%', $value);
    }

    /**
     * @return void
     */
    public function testEndsWith(): void
    {
        $operator = new EndsWith();
        $value = $operator->getValue($this->rule);

        $this->assertSame('%bar', $value);
    }

    /**
     * @return void
     */
    public function testEqual(): void
    {
        $operator = new Equal();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar', $value);
    }

    /**
     * @return void
     */
    public function testGreater(): void
    {
        $operator = new Greater();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar', $value);
    }

    /**
     * @return void
     */
    public function testGreaterOrEqual(): void
    {
        $operator = new GreaterOrEqual();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar', $value);
    }

    /**
     * @return void
     */
    public function testIn(): void
    {
        $operator = new In();
        $value = $operator->getValue($this->rule);

        $this->assertSame(['bar'], $value);
    }

    /**
     * @return void
     */
    public function testLess(): void
    {
        $operator = new Less();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar', $value);
    }

    /**
     * @return void
     */
    public function testLessOrEqual(): void
    {
        $operator = new LessOrEqual();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar', $value);
    }

    /**
     * @return void
     */
    public function testNotBeginsWith(): void
    {
        $operator = new NotBeginsWith();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar%', $value);
    }

    /**
     * @return void
     */
    public function testNotContains(): void
    {
        $operator = new NotContains();
        $value = $operator->getValue($this->rule);

        $this->assertSame('%bar%', $value);
    }

    /**
     * @return void
     */
    public function testNotEndsWith(): void
    {
        $operator = new NotEndsWith();
        $value = $operator->getValue($this->rule);

        $this->assertSame('%bar', $value);
    }

    /**
     * @return void
     */
    public function testNotEqual(): void
    {
        $operator = new NotEqual();
        $value = $operator->getValue($this->rule);

        $this->assertSame('bar', $value);
    }

    /**
     * @return void
     */
    public function testNotIn(): void
    {
        $operator = new NotIn();
        $value = $operator->getValue($this->rule);

        $this->assertSame(['bar'], $value);
    }
}
