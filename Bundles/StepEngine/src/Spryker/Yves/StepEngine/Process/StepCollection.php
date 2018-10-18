<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use ArrayIterator;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithPostConditionErrorRouteInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepCollection implements StepCollectionInterface
{
    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    protected $steps = [];

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    protected $completedSteps = [];

    /**
     * @var string
     */
    protected $errorRoute;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param string $errorRoute
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $errorRoute)
    {
        $this->urlGenerator = $urlGenerator;
        $this->errorRoute = $errorRoute;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $step
     *
     * @return $this
     */
    public function addStep(StepInterface $step)
    {
        $this->steps[] = $step;

        return $this;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return bool
     */
    public function canAccessStep(StepInterface $currentStep, Request $request, AbstractTransfer $dataTransfer)
    {
        if ($request->get('_route') === $currentStep->getStepRoute()) {
            return true;
        }

        foreach ($this->getCompletedSteps($dataTransfer) as $step) {
            if ($step->getStepRoute() === $request->get('_route')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    protected function getCompletedSteps(AbstractTransfer $dataTransfer)
    {
        $completedSteps = [];
        foreach ($this->steps as $step) {
            if ($step->postCondition($dataTransfer)) {
                $completedSteps[] = $step;
            }
        }

        return $completedSteps;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function getCurrentStep(Request $request, AbstractTransfer $dataTransfer)
    {
        foreach ($this->steps as $step) {
            if (!$step->postCondition($dataTransfer) || $request->get('_route') === $step->getStepRoute()) {
                return $step;
            }

            $this->completedSteps[] = $step;
        }

        return end($this->completedSteps);
    }

    /**
     * To prevent that this method return the first step when currentStep is the last step
     * We set the nextStep by default to the last one and check if the matched one is the last one
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function getNextStep(StepInterface $currentStep)
    {
        $nextStep = end($this->steps);

        foreach ($this->steps as $position => $step) {
            if ($step->getStepRoute() === $currentStep->getStepRoute() && $position !== count($this->steps) - 1) {
                $nextStep = $this->steps[$position + 1];
            }
        }

        return $nextStep;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function getPreviousStep(StepInterface $currentStep, ?AbstractTransfer $dataTransfer = null)
    {
        $firstStep = reset($this->steps);

        foreach ($this->steps as $position => $step) {
            if ($step->getStepRoute() === $currentStep->getStepRoute() && $position !== 0) {
                $previousStep = $this->steps[$position - 1];
            }
        }

        if (!isset($previousStep)) {
            $previousStep = $firstStep;
        }

        if ($firstStep !== $previousStep && !$this->isAccessible($previousStep, $dataTransfer)) {
            return $this->getPreviousStep($previousStep, $dataTransfer);
        }

        return $previousStep;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $step
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return bool
     */
    protected function isAccessible(StepInterface $step, ?AbstractTransfer $dataTransfer = null)
    {
        if (!$dataTransfer) {
            return true;
        }

        return $step->requireInput($dataTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     *
     * @return string
     */
    public function getCurrentUrl(StepInterface $currentStep)
    {
        return $this->urlGenerator->generate($currentStep->getStepRoute());
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return string
     */
    public function getNextUrl(StepInterface $currentStep, AbstractTransfer $dataTransfer)
    {
        if (($currentStep instanceof StepWithExternalRedirectInterface) && $currentStep->getExternalRedirectUrl()) {
            return $currentStep->getExternalRedirectUrl();
        }

        $route = $this->getNextStepRoute($currentStep, $dataTransfer);

        return $this->getUrlFromRoute($route);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return string
     */
    protected function getNextStepRoute(StepInterface $currentStep, AbstractTransfer $dataTransfer)
    {
        if ($currentStep->postCondition($dataTransfer)) {
            $nextStep = $this->getNextStep($currentStep);

            return $nextStep->getStepRoute();
        }

        if (($currentStep instanceof StepWithPostConditionErrorRouteInterface) && $currentStep->getPostConditionErrorRoute()) {
            return $currentStep->getPostConditionErrorRoute();
        }

        if ($currentStep->requireInput($dataTransfer)) {
            return $currentStep->getStepRoute();
        }

        return $this->errorRoute;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return string
     */
    public function getPreviousUrl(StepInterface $currentStep, ?AbstractTransfer $dataTransfer = null)
    {
        $stepRoute = $this->getPreviousStep($currentStep, $dataTransfer)->getStepRoute();

        return $this->getUrlFromRoute($stepRoute);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     *
     * @return string
     */
    public function getEscapeUrl(StepInterface $currentStep)
    {
        /** @var string|null $route */
        $route = $currentStep->getEscapeRoute();
        if ($route === null) {
            $route = $this->getPreviousStep($currentStep)->getStepRoute();
        }

        return $this->getUrlFromRoute($route);
    }

    /**
     * @param string $route
     *
     * @return string
     */
    protected function getUrlFromRoute($route)
    {
        return $this->urlGenerator->generate($route);
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->steps);
    }
}
