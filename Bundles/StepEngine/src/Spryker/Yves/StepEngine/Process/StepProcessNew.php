<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Spryker\Yves\StepEngine\Process\Steps\StepInterface;
use Spryker\Yves\StepEngine\Process\Steps\StepWithExternalRedirectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepProcessNew implements StepProcessInterface
{

    /**
     * @var \Spryker\Yves\StepEngine\Process\Steps\StepInterface[]
     */
    protected $steps = [];

    /**
     * @var \SplObjectStorage
     */
    protected $completedSteps = [];

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

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
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param string $errorRoute
     */
    public function __construct(
        array $steps,
        UrlGeneratorInterface $urlGenerator,
        CartClientInterface $cartClient,
        $errorRoute
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->steps = $steps;
        $this->cartClient = $cartClient;
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
        $this->setCompletedSteps();
        $currentStep = $this->getCurrentStep($request);

        if ($currentStep->preCondition($this->getQuoteTransfer()) === false) {
            $escapeRoute = $this->getEscapeRoute($currentStep);

            return $this->createRedirectResponse($this->getUrlFromRoute($escapeRoute));
        }

        if ($this->canAccessStep($request, $currentStep) === false) {
            $stepRoute = $currentStep->getStepRoute();

            return $this->createRedirectResponse($this->getUrlFromRoute($stepRoute));
        }

        if ($currentStep->requireInput($this->getQuoteTransfer()) === false) {
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
                $formCollection->provideDefaultFormData($this->getQuoteTransfer());
            }

            return $this->getTemplateVariables($currentStep, $formCollection);
        }

        $this->executeWithoutInput($currentStep, $request);

        return $this->getTemplateVariables($currentStep);
    }

    /**
     * @return void
     */
    protected function setCompletedSteps()
    {
        $quoteTransfer = $this->getQuoteTransfer();
        $this->completedSteps = new \SplObjectStorage();
        foreach ($this->steps as $step) {
            if ($step->preCondition($quoteTransfer) && $step->postCondition($quoteTransfer)) {
                $this->completedSteps->attach($step);
            }
        }
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $step
     *
     * @return bool
     */
    protected function isCompleted(StepInterface $step)
    {
        return $this->completedSteps->contains($step);
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
            if ($request->get('_route') === $step->getStepRoute() || !$this->isCompleted($step)) {
                return $step;
            }
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
        if ($this->isLastStep() === true) {
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

        $route = $this->getNextStepRoute($currentStep, $this->getQuoteTransfer());

        return $this->getUrlFromRoute($route);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getNextStepRoute(StepInterface $currentStep, QuoteTransfer $quoteTransfer)
    {
        if ($currentStep->postCondition($quoteTransfer) === true) {
            $nextStep = $this->getNextStep($currentStep);
            if ($nextStep !== null) {
                return $nextStep->getStepRoute();
            }
        }

        if ($currentStep->requireInput($quoteTransfer) === true) {
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
        if (empty($step) === false) {
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
        $quoteTransfer = $currentStep->execute($request, $this->getQuoteTransfer());
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\Steps\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $formQuoteTransfer
     *
     * @return void
     */
    protected function executeWithFormInput(
        StepInterface $currentStep,
        Request $request,
        QuoteTransfer $formQuoteTransfer
    ) {
        $quoteTransfer = $this->getQuoteTransfer();
        $quoteTransfer->fromArray($formQuoteTransfer->modifiedToArray());
        $quoteTransfer = $currentStep->execute($request, $quoteTransfer);
        $this->cartClient->storeQuote($quoteTransfer);
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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer()
    {
        return $this->cartClient->getQuote();
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
            'quoteTransfer' => $this->getQuoteTransfer(),
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
