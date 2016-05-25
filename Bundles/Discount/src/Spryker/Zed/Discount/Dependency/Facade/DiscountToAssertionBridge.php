<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Facade;

class DiscountToAssertionBridge implements DiscountToAssertionInterface
{

    /**
     * @var \Spryker\Zed\Assertion\Business\AssertionFacade
     */
    protected $assertionFacade;

    /**
     * @param \Spryker\Zed\Assertion\Business\AssertionFacade $assertionFacade
     */
    public function __construct($assertionFacade)
    {
        $this->assertionFacade = $assertionFacade;
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @throws \Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function assertNumeric($value, $message = null)
    {
        $this->assertionFacade->assertNumeric($value, $message);
    }

    /**
     *
     * @param mixed $value
     * @param string|null $message
     *
     * @throws \Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function assertNumericNotZero($value, $message = null)
    {
        $this->assertionFacade->assertNumeric($value, $message);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @throws \Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function assertString($value, $message = null)
    {
        $this->assertionFacade->assertString($value, $message);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @throws \Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function assertAlpha($value, $message = null)
    {
        $this->assertionFacade->assertAlpha($value, $message);
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @throws \Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function assertAlphaNumeric($value, $message = null)
    {
        $this->assertionFacade->assertAlphaNumeric($value, $message);
    }

}
