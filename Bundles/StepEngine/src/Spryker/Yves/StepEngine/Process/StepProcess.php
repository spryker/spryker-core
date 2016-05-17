<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Spryker\Yves\StepEngine\Process\Steps\StepInterface;
use Spryker\Yves\StepEngine\Process\Steps\StepWithExternalRedirectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepProcess implements StepProcessInterface
{

    /**
     * @var \Spryker\Yves\StepEngine\Process\Steps\StepInterface[]
     */
    protected $steps = [];

    /**
     * @var \Spryker\Yves\StepEngine\Process\Steps\StepInterface[]
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
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface[] $steps
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param string $errorRoute
     */
    public function __construct(
        array $steps,
        UrlGeneratorInterface $urlGenerator,
        $errorRoute
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->steps = $steps;
        $this->errorRoute = $errorRoute;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function process(Request $request, FormCollectionHandlerInterface $formCollection = null)
    {
        $currentStep = $this->getCurrentStep($request);

        if (!$currentStep->preCondition()) {
            $escapeRoute = $this->getEscapeRoute($currentStep);

            return $this->createRedirectResponse($this->getUrlFromRoute($escapeRoute));
        }

        if (!$this->canAccessStep($request, $currentStep)) {
            $stepRoute = $currentStep->getStepRoute();

            return $this->createRedirectResponse($this->getUrlFromRoute($stepRoute));
        }

        if (!$currentStep->requireInput()) {
            $this->executeWithoutInput($currentStep, $request);

            return $this->createRedirectResponse($this->getNextRedirectUrl($currentStep));
        }

        if ($formCollection !== null) {
            if ($formCollection->hasSubmittedForm($request)) {
                $form = $formCollection->handleRequest($request);
                if ($form->isValid()) {
                    $this->executeWithFormInput($currentStep, $request, $form->getData());
                    return $this->createRedirectResponse($this->getNextRedirectUrl($currentStep));
                }
            } else {
                $formCollection->provideDefaultFormData();
            }

            return $this->getTemplateVariables($currentStep, $formCollection);
        }

        $this->executeWithoutInput($currentStep, $request);

        return $this->getTemplateVariables($currentStep);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function getCurrentStep(Request $request)
    {
        $currentStep = null;
        foreach ($this->steps as $step) {
            if (!$step->postCondition() || $request->get('_route') === $step->getStepRoute()) {
                $currentStep = $step;
                break;
            }
            $this->completedSteps[] = $step;
        }

        if ($this->isLastStep()) {
            return $this->getLastStep();
        }

        if ($currentStep === null) {
            return $this->getFirstStep();
        }

        return $currentStep;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     *
     * @return null|\Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function getNextStep(StepInterface $currentStep)
    {
        if ($this->isLastStep()) {
            return $this->getLastStep();
        }

        foreach ($this->steps as $step) {
            if ($step->getStepRoute() === $currentStep->getStepRoute()) {
                return current($this->steps);
            }
        }

        return null;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function getPreviousStep()
    {
        end($this->completedSteps);
        $prev = current($this->completedSteps);
        reset($this->completedSteps);

        return $prev;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function getFirstStep()
    {
        reset($this->steps);
        $firstStep = current($this->steps);

        return $firstStep;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function getLastStep()
    {
        end($this->steps);
        $lastStep = current($this->steps);
        reset($this->steps);

        return $lastStep;
    }

    /**
     * @return bool
     */
    protected function isLastStep()
    {
        return (count($this->steps) === count($this->completedSteps));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     *
     * @return bool
     */
    protected function canAccessStep(Request $request, StepInterface $currentStep)
    {
        if ($request->get('_route') === $currentStep->getStepRoute()) {
            return true;
        }

        foreach ($this->completedSteps as $step) {
            if ($step->getStepRoute() === $request->get('_route')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     *
     * @return string
     */
    protected function getNextRedirectUrl(StepInterface $currentStep)
    {
        if (($currentStep instanceof StepWithExternalRedirectInterface) && !empty($currentStep->getExternalRedirectUrl())) {
            return $currentStep->getExternalRedirectUrl();
        }

        $route = $this->getNextStepRoute($currentStep);

        return $this->getUrlFromRoute($route);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     *
     * @return string
     */
    protected function getNextStepRoute(StepInterface $currentStep)
    {
        if ($currentStep->postCondition()) {
            $nextStep = $this->getNextStep($currentStep);
            if ($nextStep !== null) {
                return $nextStep->getStepRoute();
            }
        }

        if ($currentStep->requireInput()) {
            return $currentStep->getStepRoute();
        } else {
            return $this->errorRoute;
        }
    }

    /**
     * @return string
     */
    protected function getPreviousStepRoute()
    {
        $step = $this->getPreviousStep();
        if (!empty($step)) {
            return $this->getPreviousStep()->getStepRoute();
        }

        return '';
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     *
     * @return string
     */
    protected function getEscapeRoute(StepInterface $currentStep)
    {
        $escapeRoute = $currentStep->getEscapeRoute();
        if ($escapeRoute === null) {
            $escapeRoute = $this->getPreviousStep()->getStepRoute();
        }

        return $escapeRoute;
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
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function executeWithoutInput(StepInterface $currentStep, Request $request)
    {
        $currentStep->execute($request);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $formTransfer
     *
     * @return void
     */
    protected function executeWithFormInput(
        StepInterface $currentStep,
        Request $request,
        AbstractTransfer $formTransfer
    ) {
        $currentStep->execute($request, $formTransfer);
    }

    /**
     * @param string $url
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse($url)
    {
        return new RedirectResponse($url);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array
     */
    protected function getTemplateVariables(StepInterface $currentStep, FormCollectionHandlerInterface $formCollection = null)
    {
        $templateVariables = [
            'previousStepUrl' => $this->getUrlFromRoute($this->getPreviousStepRoute()),
            'dataClass' => ($formCollection) ? $formCollection->getDataClass() : null,
        ];
        $templateVariables = array_merge($templateVariables, $currentStep->getTemplateVariables());

        if ($formCollection !== null) {
            foreach ($formCollection->getForms() as $form) {
                $templateVariables[$form->getName()] = $form->createView();
            }
        }

        return $templateVariables;
    }

}
