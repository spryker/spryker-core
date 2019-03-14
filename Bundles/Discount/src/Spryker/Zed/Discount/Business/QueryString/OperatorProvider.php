<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

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
        return new Contains();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\DoesNotContain
     */
    protected function createDoesNotContain()
    {
        return new DoesNotContain();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal
     */
    protected function createEqual()
    {
        return new Equal();
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
        return new Less();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\LessEqual
     */
    protected function createLessEqual()
    {
        return new LessEqual();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\Greater
     */
    protected function createGreater()
    {
        return new Greater();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\GreaterEqual
     */
    protected function createGreaterEqual()
    {
        return new GreaterEqual();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Comparator\NotEqual
     */
    protected function createNotEqual()
    {
        return new NotEqual();
    }
}
