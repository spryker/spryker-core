<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Process;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;

interface StepBreadcrumbGeneratorInterface
{
    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $dataTransfer
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface|null $currentStep
     *
     * @return \Generated\Shared\Transfer\StepBreadcrumbsTransfer
     */
    public function generateStepBreadcrumbs(StepCollectionInterface $stepCollection, ?AbstractTransfer $dataTransfer = null, ?StepInterface $currentStep = null);
}
