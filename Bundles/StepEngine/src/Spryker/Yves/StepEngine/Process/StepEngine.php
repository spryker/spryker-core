<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class StepEngine implements StepEngineInterface
{
    const TEMPLATE_VARIABLE_PREVIOUS_STEP_URL = 'previousStepUrl';
    const TEMPLATE_VARIABLE_STEP_BREADCRUMBS = 'stepBreadcrumbs';

    /**
     * @var \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    protected $stepCollection;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface
     */
    protected $dataContainer;

    /**
     * @var \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface
     */
    protected $stepBreadcrumbGenerator;

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     * @param \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface $dataContainer
     * @param \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface|null $stepBreadcrumbGenerator
     */
    public function __construct(
        StepCollectionInterface $stepCollection,
        DataContainerInterface $dataContainer,
        ?StepBreadcrumbGeneratorInterface $stepBreadcrumbGenerator = null
    ) {
        $this->stepCollection = $stepCollection;
        $this->dataContainer = $dataContainer;
        $this->stepBreadcrumbGenerator = $stepBreadcrumbGenerator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function process(Request $request, ?FormCollectionHandlerInterface $formCollection = null)
    {
        $dataTransfer = $this->dataContainer->get();
        $response = $this->runProcess($request, $dataTransfer, $formCollection);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function runProcess(Request $request, AbstractTransfer $dataTransfer, ?FormCollectionHandlerInterface $formCollection = null)
    {
        $currentStep = $this->stepCollection->getCurrentStep($request, $dataTransfer);

        if (!$currentStep->preCondition($dataTransfer)) {
            return $this->createRedirectResponse($this->stepCollection->getEscapeUrl($currentStep));
        }

        if (!$this->stepCollection->canAccessStep($currentStep, $request, $dataTransfer)) {
            return $this->createRedirectResponse($this->stepCollection->getCurrentUrl($currentStep));
        }

        if (!$currentStep->requireInput($dataTransfer)) {
            $this->executeWithoutInput($currentStep, $request, $dataTransfer);

            return $this->createRedirectResponse($this->stepCollection->getNextUrl($currentStep, $dataTransfer));
        }
        if (!$this->isRequestedStep($request, $currentStep)) {
            return $this->createRedirectResponse($this->stepCollection->getCurrentUrl($currentStep));
        }

        if (!$formCollection) {
            $this->executeWithoutInput($currentStep, $request, $dataTransfer);

            return $this->getTemplateVariables($currentStep, $dataTransfer);
        }

        if ($formCollection->hasSubmittedForm($request, $dataTransfer)) {
            $form = $formCollection->handleRequest($request, $dataTransfer);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->executeWithFormInput($currentStep, $request, $dataTransfer, $form->getData());

                return $this->createRedirectResponse($this->stepCollection->getNextUrl($currentStep, $dataTransfer));
            }
        } else {
            $formCollection->provideDefaultFormData($dataTransfer);
        }

        return $this->getTemplateVariables($currentStep, $dataTransfer, $formCollection);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     *
     * @return bool
     */
    protected function isRequestedStep(Request $request, StepInterface $currentStep)
    {
        return $request->get('_route') === $currentStep->getStepRoute();
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return void
     */
    protected function executeWithoutInput(StepInterface $currentStep, Request $request, AbstractTransfer $dataTransfer)
    {
        $dataTransfer = $currentStep->execute($request, $dataTransfer);

        $this->dataContainer->set($dataTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $formTransfer
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
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array
     */
    protected function getTemplateVariables(StepInterface $currentStep, AbstractTransfer $dataTransfer, ?FormCollectionHandlerInterface $formCollection = null)
    {
        $templateVariables[self::TEMPLATE_VARIABLE_PREVIOUS_STEP_URL] = $this->stepCollection->getPreviousUrl($currentStep, $dataTransfer);
        if ($this->stepBreadcrumbGenerator) {
            $templateVariables[self::TEMPLATE_VARIABLE_STEP_BREADCRUMBS] = $this->stepBreadcrumbGenerator->generateStepBreadcrumbs(
                $this->stepCollection,
                $dataTransfer,
                $currentStep
            );
        }
        $templateVariables = array_merge($templateVariables, $currentStep->getTemplateVariables($dataTransfer));

        if ($formCollection !== null) {
            foreach ($formCollection->getForms($dataTransfer) as $form) {
                $templateVariables[$form->getName()] = $form->createView();
            }
        }

        return $templateVariables;
    }
}
