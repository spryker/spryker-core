<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class ModuleOverviewController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'moduleOverviewTransferCollection' => $this->getFacade()->getModuleOverview(),
        ]);
    }
}
