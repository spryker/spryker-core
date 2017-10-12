<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

interface DataImportStepAwareInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface $dataImportStep
     *
     * @return $this
     */
    public function addStep(DataImportStepInterface $dataImportStep);
}
