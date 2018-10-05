<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Generated\Shared\Transfer\StepBreadcrumbItemTransfer;
use Generated\Shared\Transfer\StepBreadcrumbsTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;

class StepBreadcrumbGenerator implements StepBreadcrumbGeneratorInterface
{
    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface|null $currentStep
     *
     * @return \Generated\Shared\Transfer\StepBreadcrumbsTransfer
     */
    public function generateStepBreadcrumbs(StepCollectionInterface $stepCollection, ?AbstractTransfer $dataTransfer = null, ?StepInterface $currentStep = null)
    {
        $stepBreadcrumbTransfer = new StepBreadcrumbsTransfer();

        foreach ($this->getStepsWithBreadcrumb($stepCollection, $dataTransfer) as $stepWithBreadcrumb) {
            $stepBreadcrumbItemTransfer = $this->createStepBreadcrumbItem($stepWithBreadcrumb);
            $stepBreadcrumbItemTransfer->setIsEnabled($this->isEnabled($stepWithBreadcrumb, $dataTransfer));
            $stepBreadcrumbItemTransfer->setIsActive($this->isActive($stepWithBreadcrumb, $currentStep));

            $stepBreadcrumbTransfer->addBreadcrumb($stepBreadcrumbItemTransfer);
        }

        return $stepBreadcrumbTransfer;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface[]
     */
    protected function getStepsWithBreadcrumb(StepCollectionInterface $stepCollection, ?AbstractTransfer $dataTransfer = null)
    {
        $breadcrumbSteps = [];
        foreach ($stepCollection as $step) {
            if ($step instanceof StepWithBreadcrumbInterface && !$this->isHidden($step, $dataTransfer)) {
                $breadcrumbSteps[] = $step;
            }
        }

        return $breadcrumbSteps;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface $stepWithBreadcrumb
     *
     * @return \Generated\Shared\Transfer\StepBreadcrumbItemTransfer
     */
    protected function createStepBreadcrumbItem(StepWithBreadcrumbInterface $stepWithBreadcrumb)
    {
        $stepBreadcrumbItemTransfer = new StepBreadcrumbItemTransfer();
        $stepBreadcrumbItemTransfer
            ->setTitle($stepWithBreadcrumb->getBreadcrumbItemTitle())
            ->setRoute($stepWithBreadcrumb->getStepRoute());

        return $stepBreadcrumbItemTransfer;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface $stepWithBreadcrumb
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return bool
     */
    protected function isEnabled(StepWithBreadcrumbInterface $stepWithBreadcrumb, ?AbstractTransfer $dataTransfer = null)
    {
        if (!$dataTransfer) {
            return false;
        }

        return $stepWithBreadcrumb->isBreadcrumbItemEnabled($dataTransfer);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface $stepWithBreadcrumb
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface|null $currentStep
     *
     * @return bool
     */
    protected function isActive(StepWithBreadcrumbInterface $stepWithBreadcrumb, ?StepInterface $currentStep = null)
    {
        return $stepWithBreadcrumb == $currentStep;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface $stepWithBreadcrumb
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $dataTransfer
     *
     * @return bool
     */
    protected function isHidden(StepWithBreadcrumbInterface $stepWithBreadcrumb, ?AbstractTransfer $dataTransfer = null)
    {
        if (!$dataTransfer) {
            return false;
        }

        return $stepWithBreadcrumb->isBreadcrumbItemHidden($dataTransfer);
    }
}
