<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication;

use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 */
class DataImportCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleInterface
     */
    public function getConsoleMessenger()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::CONSOLE_LOGGER);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleInterface
     */
    public function getTimer()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::TIMER);
    }

}
