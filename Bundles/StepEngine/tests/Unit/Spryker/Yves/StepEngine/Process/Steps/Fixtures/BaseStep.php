<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Process\Steps\Fixtures;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Yves\StepEngine\Process\Steps\BaseStep as AbstractBaseStep;

class BaseStep extends AbstractBaseStep
{

    /**
     * @return AbstractTransfer
     */
    public function getDataClass()
    {
        return new TestTransfer();
    }

    /**
     * @return bool
     */
    public function preCondition()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function requireInput()
    {
        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer|null $transfer
     *
     * @return void
     */
    public function execute(Request $request, AbstractTransfer $transfer = null)
    {
    }

    /**
     * @return bool
     */
    public function postCondition()
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isCartEmpty(QuoteTransfer $quoteTransfer)
    {
        return parent::isCartEmpty($quoteTransfer);
    }

}
