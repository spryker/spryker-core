<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ManualOrderEntryGuiConfig extends AbstractBundleConfig
{
    protected const PREV_STEP_NAME = 'prev-step';
    protected const NEXT_STEP_NAME = 'next-step';

    /**
     * @return string
     */
    public function getPrevStepName()
    {
        return static::PREV_STEP_NAME;
    }

    /**
     * @return string
     */
    public function getNextStepName()
    {
        return static::NEXT_STEP_NAME;
    }
}
