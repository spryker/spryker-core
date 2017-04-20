<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder;

use Spryker\Shared\FactFinder\FactFinderConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FactFinderConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->get(FactFinderConstants::ENV);
    }

    /**
     * @return array
     */
    public function getFactFinderConfiguration()
    {
        return $this->get(FactFinderConstants::ENV . $this->getEnv());
    }

    /**
     * @return string
     */
    public function getCsvDirectory()
    {
        return $this->get(FactFinderConstants::CSV_DIRECTORY);
    }

    /**
     * @return string
     */
    public function getExportQueryLimit()
    {
        return $this->get(FactFinderConstants::EXPORT_QUERY_LIMIT);
    }

    /**
     * @return string
     */
    public function getExportFileNamePrefix()
    {
        return $this->get(FactFinderConstants::EXPORT_FILE_NAME_PREFIX);
    }

    /**
     * @return string
     */
    public function getExportFileNameDelimiter()
    {
        return $this->get(FactFinderConstants::EXPORT_FILE_NAME_DELIMITER);
    }

    /**
     * @return string
     */
    public function getExportFileExtension()
    {
        return $this->get(FactFinderConstants::EXPORT_FILE_EXTENSION);
    }

}
