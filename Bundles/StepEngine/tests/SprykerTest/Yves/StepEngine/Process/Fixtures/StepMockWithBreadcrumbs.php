<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process\Fixtures;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;

class StepMockWithBreadcrumbs extends StepMock implements StepWithBreadcrumbInterface
{
    /**
     * @return string
     */
    public function getBreadcrumbItemTitle(): string
    {
        return $this->getStepRoute();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $quoteTransfer): bool
    {
        return $this->postCondition($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $quoteTransfer): bool
    {
        return !$this->requireInput($quoteTransfer);
    }
}
