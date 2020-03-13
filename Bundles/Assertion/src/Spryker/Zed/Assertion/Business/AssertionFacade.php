<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Assertion\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Assertion\Business\AssertionBusinessFactory getFactory()
 */
class AssertionFacade extends AbstractFacade implements AssertionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertNumeric($value, $message = null)
    {
        $this->getFactory()->createAssertion()->assertNumeric($value, $message);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertNumericNotZero($value, $message = null)
    {
        $this->getFactory()->createAssertion()->assertNumericNotZero($value, $message);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertString($value, $message = null)
    {
        $this->getFactory()->createAssertion()->assertString($value, $message);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertAlpha($value, $message = null)
    {
        $this->getFactory()->createAssertion()->assertAlpha($value, $message);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertAlphaNumeric($value, $message = null)
    {
        $this->getFactory()->createAssertion()->assertAlphaNumeric($value, $message);
    }
}
