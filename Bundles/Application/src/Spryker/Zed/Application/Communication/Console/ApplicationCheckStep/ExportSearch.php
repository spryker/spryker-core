<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 */
class ExportSearch extends AbstractApplicationCheckStep
{

    /**
     * @return void
     */
    public function run()
    {
        $this->getFacade()->runCheckStepExportSearch($this->logger);
    }

}
