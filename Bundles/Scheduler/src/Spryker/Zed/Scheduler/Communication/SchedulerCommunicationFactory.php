<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 */
class SchedulerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return FilesystemLoader
     */
    public function createFilesystemLoader()
    {
        return new FilesystemLoader($this->getConfig()->getTemplatePaths());
    }
}
