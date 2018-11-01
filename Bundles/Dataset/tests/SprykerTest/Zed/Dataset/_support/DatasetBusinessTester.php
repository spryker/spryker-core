<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Dataset;

use Codeception\Actor;
use Codeception\Configuration;
use Generated\Shared\Transfer\DatasetFilePathTransfer;
use Generated\Shared\Transfer\DatasetLocalizedAttributeTransfer;
use Generated\Shared\Transfer\DatasetTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class DatasetBusinessTester extends Actor
{
    use _generated\DatasetBusinessTesterActions;

    /**
     * array $data
     *
     * @return \Generated\Shared\Transfer\DatasetFilePathTransfer
     */
    public function createDatasetFilePathTransfer(): DatasetFilePathTransfer
    {
        $datasetFilePathTransfer = new DatasetFilePathTransfer();
        $datasetFilePathTransfer->setFilePath(Configuration::dataDir() . 'dashboard_data_file.csv');

        return $datasetFilePathTransfer;
    }

    /**
     * array $data
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function createDatasetTransfer(): DatasetTransfer
    {
        $datasetEntity = new DatasetTransfer();
        $datasetEntity->setName(sprintf('Test Dashboard %s', rand(1, 999)));
        $datasetEntity->setIsActive(true);

        $this->addDatasetLocalizedAttributes($datasetEntity);

        return $datasetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetEntity
     *
     * @return void
     */
    protected function addDatasetLocalizedAttributes(DatasetTransfer $datasetEntity): void
    {
        $localizedAttributes = $this->getLocator()->locale()->facade()->getAvailableLocales();
        foreach ($localizedAttributes as $idLocale => $localizedAttribute) {
            $datasetLocalizedAttributesTransfer = new DatasetLocalizedAttributeTransfer();
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setIdLocale($idLocale);
            $datasetLocalizedAttributesTransfer->setLocale($localeTransfer);
            $datasetLocalizedAttributesTransfer->setTitle($localizedAttribute);
            $datasetEntity->addDatasetLocalizedAttribute($datasetLocalizedAttributesTransfer);
        }
    }
}
