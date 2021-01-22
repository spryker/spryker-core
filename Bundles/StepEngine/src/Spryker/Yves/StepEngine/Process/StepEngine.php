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
    public const TEMPLATE_VARIABLE_PREVIOUS_STEP_URL = 'previousStepUrl';
    public const TEMPLATE_VARIABLE_STEP_BREADCRUMBS = 'stepBreadcrumbs';

    /**
     * @var \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    protected $stepCollection;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface
     */
    protected $dataContainer;

    /**
     * @var \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface|null
     */
    protected $stepBreadcrumbGenerator;

    /**
     * @var \Spryker\Yves\StepEngineExtension\Dependency\Plugin\StepEnginePreRenderPluginInterface[]
     */
    protected $stepEnginePreRenderPlugins;

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     * @param \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface $dataContainer
     * @param \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface|null $stepBreadcrumbGenerator
     * @param \Spryker\Yves\StepEngineExtension\Dependency\Plugin\StepEnginePreRenderPluginInterface[] $stepEnginePreRenderPlugins
     */
    public function __construct(
        StepCollectionInterface $stepCollection,
        DataContainerInterface $dataContainer,
        ?StepBreadcrumbGeneratorInterface $stepBreadcrumbGenerator = null,
        array $stepEnginePreRenderPlugins = []
    ) {
        $this->stepCollection = $stepCollection;
        $this->dataContainer = $dataContainer;
        $this->stepBreadcrumbGenerator = $stepBreadcrumbGenerator;
        $this->stepEnginePreRenderPlugins = $stepEnginePreRenderPlugins;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function runProcess(Request $request, AbstractTransfer $quoteTransfer, ?FormCollectionHandlerInterface $formCollection = null)
    {
        $currentStep = $this->stepCollection->getCurrentStep($request, $quoteTransfer);

        if (!$currentStep->preCondition($quoteTransfer)) {
            return $this->createRedirectResponse($this->stepCollection->getEscapeUrl($currentStep));
        }

        if (!$this->stepCollection->canAccessStep($currentStep, $request, $quoteTransfer)) {
            return $this->createRedirectResponse($this->stepCollection->getCurrentUrl($currentStep));
        }

        if (!$currentStep->requireInput($quoteTransfer)) {
            $quoteTransfer = $this->executeWithoutInput($currentStep, $request, $quoteTransfer);

            return $this->createRedirectResponse($this->stepCollection->getNextUrl($currentStep, $quoteTransfer));
        }
        if (!$this->isRequestedStep($request, $currentStep)) {
            return $this->createRedirectResponse($this->stepCollection->getCurrentUrl($currentStep));
        }

        $quoteTransfer = $this->executeStepEnginePreRenderPlugins($quoteTransfer);

        if (!$formCollection) {
            $quoteTransfer = $this->executeWithoutInput($currentStep, $request, $quoteTransfer);

            return $this->getTemplateVariables($currentStep, $quoteTransfer);
        }

        if ($formCollection->hasSubmittedForm($request, $quoteTransfer)) {
            $form = $formCollection->handleRequest($request, $quoteTransfer);
            if ($form->isSubmitted() && $form->isValid()) {
                $quoteTransfer = $this->executeWithFormInput($currentStep, $request, $quoteTransfer, $form->getData());

                return $this->createRedirectResponse($this->stepCollection->getNextUrl($currentStep, $quoteTransfer));
            }
        } else {
            $formCollection->provideDefaultFormData($quoteTransfer);
        }

        return $this->getTemplateVariables($currentStep, $quoteTransfer, $formCollection);
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeWithoutInput(StepInterface $currentStep, Request $request, AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = $currentStep->execute($request, $quoteTransfer);

        $this->dataContainer->set($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $formTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeWithFormInput(
        StepInterface $currentStep,
        Request $request,
        AbstractTransfer $quoteTransfer,
        AbstractTransfer $formTransfer
    ) {
        $quoteTransfer->fromArray($formTransfer->modifiedToArray());
        $quoteTransfer = $currentStep->execute($request, $formTransfer);

        $this->dataContainer->set($quoteTransfer);

        return $quoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array
     */
    protected function getTemplateVariables(StepInterface $currentStep, AbstractTransfer $quoteTransfer, ?FormCollectionHandlerInterface $formCollection = null)
    {
        $templateVariables = [];

        $templateVariables[self::TEMPLATE_VARIABLE_PREVIOUS_STEP_URL] = $this->stepCollection->getPreviousUrl($currentStep, $quoteTransfer);
        if ($this->stepBreadcrumbGenerator) {
            $templateVariables[self::TEMPLATE_VARIABLE_STEP_BREADCRUMBS] = $this->stepBreadcrumbGenerator->generateStepBreadcrumbs(
                $this->stepCollection,
                $quoteTransfer,
                $currentStep
            );
        }
        $templateVariables = array_merge($templateVariables, $currentStep->getTemplateVariables($quoteTransfer));

        if ($formCollection !== null) {
            foreach ($formCollection->getForms($quoteTransfer) as $form) {
                $templateVariables[$form->getName()] = $form->createView();
            }
        }

        return $templateVariables;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeStepEnginePreRenderPlugins(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        foreach ($this->stepEnginePreRenderPlugins as $stepEnginePreRenderPlugin) {
            $quoteTransfer = $stepEnginePreRenderPlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
