<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\DataExport;

use Codeception\Actor;
use Codeception\Configuration;
use Spryker\Service\DataExport\DataExportServiceInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class DataExportServiceTester extends Actor
{
    use _generated\DataExportServiceTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return \Spryker\Service\DataExport\DataExportServiceInterface
     */
    public function getService(): DataExportServiceInterface
    {
        return $this->getLocator()->dataExport()->service();
    }

    /**
     * @param string $directoryName
     *
     * @return void
     */
    public function removeCreatedFiles(string $directoryName): void
    {
        $targetDirectory = Configuration::outputDir() . $directoryName;
        exec('rm -rf ' . $targetDirectory);
    }

    /**
     * @param string $filePath
     *
     * @return array
     */
    public function getCsvFileData(string $filePath): array
    {
        $file = fopen($filePath, 'rb');
        $fileData = [];
        while ($row = fgetcsv($file)) {
            $fileData[] = $row;
        }
        fclose($file);

        return $fileData;
    }
}
