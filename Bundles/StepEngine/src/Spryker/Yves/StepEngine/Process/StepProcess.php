<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface;
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
     * @var DataContainerInterface
     */
    protected $dataContainer;

    /**
     * @param array $steps
     * @param \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface $dataContainer
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param $errorRoute
     */
    public function __construct(
        array $steps,
        DataContainerInterface $dataContainer,
        UrlGeneratorInterface $urlGenerator,
        $errorRoute
    ) {
        $this->steps = $steps;
        $this->dataContainer = $dataContainer;
        $this->urlGenerator = $urlGenerator;
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
        $dataTransfer = $this->dataContainer->get();
        $response = $this->runProcess($request, $dataTransfer, $formCollection);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function runProcess(Request $request, AbstractTransfer $dataTransfer, FormCollectionHandlerInterface $formCollection = null)
    {
        $currentStep = $this->getCurrentStep($request, $dataTransfer);

        if (!$currentStep->preCondition($dataTransfer)) {
            $escapeRoute = $this->getEscapeRoute($currentStep);

            return $this->createRedirectResponse($this->getUrlFromRoute($escapeRoute));
        }

        if (!$this->canAccessStep($request, $currentStep)) {
            $stepRoute = $currentStep->getStepRoute();

            return $this->createRedirectResponse($this->getUrlFromRoute($stepRoute));
        }

        if (!$currentStep->requireInput($dataTransfer)) {
            $this->executeWithoutInput($currentStep, $request, $dataTransfer);

            return $this->createRedirectResponse($this->getNextRedirectUrl($currentStep, $dataTransfer));
        }

        if (!$formCollection) {
            $this->executeWithoutInput($currentStep, $request, $dataTransfer);

            return $this->getTemplateVariables($currentStep, $dataTransfer);
        }

        if ($formCollection->hasSubmittedForm($request, $dataTransfer)) {
            $form = $formCollection->handleRequest($request, $dataTransfer);
            if ($form->isValid()) {
                $this->executeWithFormInput($currentStep, $request, $dataTransfer, $form->getData());

                return $this->createRedirectResponse($this->getNextRedirectUrl($currentStep, $dataTransfer));
            }
        } else {
            $formCollection->provideDefaultFormData($dataTransfer);
        }

        return $this->getTemplateVariables($currentStep, $dataTransfer, $formCollection);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function getCurrentStep(Request $request, AbstractTransfer $dataTransfer)
    {
        $currentStep = null;
        foreach ($this->steps as $step) {
            if (!$step->postCondition($dataTransfer) || $request->get('_route') === $step->getStepRoute()) {
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return string
     */
    protected function getNextRedirectUrl(StepInterface $currentStep, AbstractTransfer $dataTransfer)
    {
        if (($currentStep instanceof StepWithExternalRedirectInterface) && !empty($currentStep->getExternalRedirectUrl())) {
            return $currentStep->getExternalRedirectUrl();
        }

        $route = $this->getNextStepRoute($currentStep, $dataTransfer);

        return $this->getUrlFromRoute($route);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return string
     */
    protected function getNextStepRoute(StepInterface $currentStep, AbstractTransfer $dataTransfer)
    {
        if ($currentStep->postCondition($dataTransfer)) {
            $nextStep = $this->getNextStep($currentStep);
            if ($nextStep !== null) {
                return $nextStep->getStepRoute();
            }
        }

        if ($currentStep->requireInput($dataTransfer)) {
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return void
     */
    protected function executeWithoutInput(StepInterface $currentStep, Request $request, AbstractTransfer $dataTransfer)
    {
        $currentStep->execute($request, $dataTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Shared\Transfer\AbstractTransfer $formTransfer
     *
     * @return void
     */
    protected function executeWithFormInput(
        StepInterface $currentStep,
        Request $request,
        AbstractTransfer $dataTransfer,
        AbstractTransfer $formTransfer
    ) {
        $dataTransfer->fromArray($formTransfer->modifiedToArray());
        $dataTransfer = $currentStep->execute($request, $formTransfer);

        $this->dataContainer->set($dataTransfer);
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array
     */
    protected function getTemplateVariables(StepInterface $currentStep, AbstractTransfer $dataTransfer, FormCollectionHandlerInterface $formCollection = null)
    {
        $templateVariables = [
            'previousStepUrl' => $this->getUrlFromRoute($this->getPreviousStepRoute()),
        ];
        $templateVariables = array_merge($templateVariables, $currentStep->getTemplateVariables($dataTransfer));

        if ($formCollection !== null) {
            foreach ($formCollection->getForms($dataTransfer) as $form) {
                $templateVariables[$form->getName()] = $form->createView();
            }
        }

        return $templateVariables;
    }

}
