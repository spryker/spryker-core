<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\StepOverride;

use Codeception\Step;
use Codeception\Step\Assertion;

class AssertionExtender extends Assertion implements StepDescriptionExtender
{
    /**
     * @var string
     */
    protected $stepDescription;

    /**
     * @return string
     */
    protected function getStepDescription(): string
    {
        return $this->stepDescription;
    }

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function setStepDescription(string $stepDescription): Step
    {
        $this->stepDescription = $stepDescription;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    protected function humanize($text): string
    {
        return $this->getStepDescription() . parent::humanize($text);
    }
}
