<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getFileName($locale)
    {
        $config = $this->getConfig();
        $filePrefix = $config->getExportFileNamePrefix();
        $delimiter = $config->getExportFileNameDelimiter();
        $extension = $config->getExportFileExtension();

        return $filePrefix . $delimiter . $locale . $extension;
    }

    /**
     * @return string
     */
    public function getCsvDirectoryPath()
    {
        return $this->getConfig()
            ->getCsvDirectory();
    }

    /**
     * @param string $locale
     *
     * @return bool|string
     */
    public function getFileContent($locale)
    {
        $factFinderFolder = $this->getCsvDirectoryPath();
        $fileName = $this->getFileName($locale);

        $fileNamePath = $factFinderFolder . $fileName;

        try {
            $content = file_get_contents($fileNamePath);
        } catch (\Exception $exception) {
            $content = false;
        }

        return $content;
    }

}
