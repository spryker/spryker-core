<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ManualOrderEntryGuiConfig extends AbstractBundleConfig
{
    protected const PREVIOUS_STEP_NAME = 'previous-step';
    protected const NEXT_STEP_NAME = 'next-step';

    /**
     * @return string
     */
    public function getPreviousStepName(): string
    {
        return static::PREVIOUS_STEP_NAME;
    }

    /**
     * @return string
     */
    public function getNextStepName(): string
    {
        return static::NEXT_STEP_NAME;
    }
}
