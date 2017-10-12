<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

interface DataImportStepBeforeExecuteInterface
{
    /**
     * @return void
     */
    public function beforeExecute();
}
