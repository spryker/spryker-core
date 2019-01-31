<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Contains;
use Spryker\Zed\Discount\Business\QueryString\Comparator\DoesNotContain;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Equal;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Greater;
use Spryker\Zed\Discount\Business\QueryString\Comparator\GreaterEqual;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn;
use Spryker\Zed\Discount\Business\QueryString\Comparator\Less;
use Spryker\Zed\Discount\Business\QueryString\Comparator\LessEqual;
use Spryker\Zed\Discount\Business\QueryString\Comparator\NotEqual;

class OperatorProvider
{
    /**
     * @var \Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface
     */
    protected $floatRounder;

    /**
     * @param \Spryker\Zed\Discount\Business\Calculator\FloatRounderInterface $floatRounder
     */
    public function __construct(FloatRounderInterface $floatRounder)
    {
        $this->floatRounder = $floatRounder;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\ComparatorInterface[]
     */
    public function createComparators()
    {
        return [
            $this->createEqual(),
            $this->createNotEqual(),
            $this->createContains(),
            $this->createDoesNotContain(),
            $this->createIsIn(),
            $this->createIsNotIn(),
            $this->createLess(),
            $this->createLessEqual(),
            $this->createGreater(),
            $this->createGreaterEqual(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Contains
     */
    protected function createContains()
    {
        return new Contains($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\DoesNotContain
     */
    protected function createDoesNotContain()
    {
        return new DoesNotContain($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal
     */
    protected function createEqual()
    {
        return new Equal($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn
     */
    protected function createIsIn()
    {
        return new IsIn();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn
     */
    protected function createIsNotIn()
    {
        return new IsNotIn();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Less
     */
    protected function createLess()
    {
        return new Less($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\LessEqual
     */
    protected function createLessEqual()
    {
        return new LessEqual($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Greater
     */
    protected function createGreater()
    {
        return new Greater($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\GreaterEqual
     */
    protected function createGreaterEqual()
    {
        return new GreaterEqual($this->floatRounder);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\NotEqual
     */
    protected function createNotEqual()
    {
        return new NotEqual($this->floatRounder);
    }
}
