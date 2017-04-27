<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\FactFinder\Business\Exporter\FactFinderProductExporter;
use Spryker\Zed\FactFinder\Business\Writer\AbstractFileWriter;
use Spryker\Zed\FactFinder\Business\Writer\CsvFileWriter;
use Spryker\Zed\FactFinder\FactFinderDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function createCsvFile(LocaleTransfer $localeTransfer)
    {
        return $this->createFactFinderProductExporter(new CsvFileWriter(), $localeTransfer)
            ->export();
    }

    /**
     * @return \Spryker\Zed\FactFinder\FactFinderConfig
     */
    public function getFactFinderConfig()
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface
     */
    public function getFactFinderQueryContainer()
    {
        return $this->getQueryContainer();
    }

    /**
     * @return \Spryker\Zed\FactFinder\Dependency\Facade\FactFinderToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::LOCALE_FACADE);
    }

    /**
     * @param \Spryker\Zed\FactFinder\Business\Writer\AbstractFileWriter $fileWriter
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\FactFinder\Business\Exporter\FactFinderProductExporter
     */
    protected function createFactFinderProductExporter(AbstractFileWriter $fileWriter, LocaleTransfer $localeTransfer)
    {
        return new FactFinderProductExporter(
            $fileWriter,
            $localeTransfer,
            $this->getConfig(),
            $this->getQueryContainer()
        );
    }

}
