<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Orm\Zed\Locale\Persistence\Base\SpyLocale;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\FactFinder\Business\Exporter\FactFinderProductExporterPlugin;
use Spryker\Zed\FactFinder\Business\Writer\AbstractFileWriter;
use Spryker\Zed\FactFinder\Business\Writer\CsvFileWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param \Orm\Zed\Locale\Persistence\Base\SpyLocale $locale
     *
     * @return void
     */
    public function createCsvFile(SpyLocale $locale)
    {
        return $this->createFactFinderProductExporter(new CsvFileWriter(), $locale)
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
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocaleQuery()
    {
        return SpyLocaleQuery::create()->addSelfSelectColumns();
    }

    /**
     * @param string $filePath
     *
     * @return \Spryker\Zed\FactFinder\Business\Writer\CsvFileWriter
     */
    public function createCsvWriter($filePath)
    {
        return new CsvFileWriter($filePath);
    }

    /**
     * @param \Spryker\Zed\FactFinder\Business\Writer\AbstractFileWriter $fileWriter
     *
     * @return \Spryker\Zed\FactFinder\Business\Exporter\FactFinderProductExporterPlugin
     */
    protected function createFactFinderProductExporter(AbstractFileWriter $fileWriter, SpyLocale $locale)
    {
        return new FactFinderProductExporterPlugin($fileWriter, $locale);
    }

}
