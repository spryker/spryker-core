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

class StepEngineWithBreadcrumb extends StepEngine
{

    const TEMPLATE_VARIABLE_STEP_BREADCRUMB = 'stepBreadcrumb';

    /**
     * @var \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface
     */
    protected $stepBreadcrumbGenerator;

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     * @param \Spryker\Yves\StepEngine\Dependency\DataContainer\DataContainerInterface $dataContainer
     * @param \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface $stepBreadcrumbGenerator
     */
    public function __construct(
        StepCollectionInterface $stepCollection,
        DataContainerInterface $dataContainer,
        StepBreadcrumbGeneratorInterface $stepBreadcrumbGenerator
    ) {
        parent::__construct($stepCollection, $dataContainer);

        $this->stepBreadcrumbGenerator = $stepBreadcrumbGenerator;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     * @param \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface|null $formCollection
     *
     * @return array
     */
    protected function getTemplateVariables(StepInterface $currentStep, AbstractTransfer $dataTransfer, FormCollectionHandlerInterface $formCollection = null)
    {
        $templateVariables = parent::getTemplateVariables($currentStep, $dataTransfer, $formCollection);

        $templateVariables[self::TEMPLATE_VARIABLE_STEP_BREADCRUMB] = $this->stepBreadcrumbGenerator->generateStepBreadcrumb(
            $this->stepCollection,
            $dataTransfer,
            $currentStep
        );

        return $templateVariables;
    }

}
