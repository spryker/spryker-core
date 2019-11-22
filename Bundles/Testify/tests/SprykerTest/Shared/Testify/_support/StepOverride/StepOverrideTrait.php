<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\StepOverride;

trait StepOverrideTrait
{
    /**
     * @var \SprykerTest\Shared\Testify\StepOverride\StepOverrider|null
     */
    protected $stepOverrider;

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function amSure(string $stepDescription): self
    {
        return $this->overrideStep('am sure ' . $stepDescription);
    }

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function assume(string $stepDescription): self
    {
        return $this->overrideStep('assume ' . $stepDescription);
    }

    /**
     * @return static
     */
    public function whenI(): self
    {
        if ($this->stepOverrider !== null) {
            $this->stepOverrider->addPreposition(' when I ');
        }

        return $this;
    }

    /**
     * @return static
     */
    public function ifI(): self
    {
        if ($this->stepOverrider !== null) {
            $this->stepOverrider->addPreposition(' if I ');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getScenario()
    {
        return $this->stepOverrider ?? parent::getScenario();
    }

    /**
     * @param string $stepDescription
     *
     * @return static
     */
    public function overrideStep(string $stepDescription): self
    {
        $scenario = $this->getScenario();

        $this->stepOverrider = new StepOverrider($scenario, $stepDescription, [$this, 'releaseStep']);

        return $this;
    }

    /**
     * @return static
     */
    public function releaseStep(): self
    {
        $this->stepOverrider = null;

        return $this;
    }
}
