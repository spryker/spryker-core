<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\StepOverride;

use Codeception\Step;

interface StepDescriptionExtender
{
    /**
     * @param string $stepDescription
     *
     * @return \Codeception\Step
     */
    public function setStepDescription(string $stepDescription): Step;
}
