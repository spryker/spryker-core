<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step;

use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\DataSet\SspAssetDataSetInterface;

class ExternalImageUrlValidationStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $externalImageUrl = $dataSet[SspAssetDataSetInterface::COLUMN_EXTERNAL_IMAGE_URL] ?? '';

        if (!$externalImageUrl) {
            $dataSet[SspAssetDataSetInterface::COLUMN_EXTERNAL_IMAGE_URL] = null;

            return;
        }

        if (!filter_var($externalImageUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidDataException(sprintf('Invalid external image URL: "%s"', $externalImageUrl));
        }

        $dataSet[SspAssetDataSetInterface::COLUMN_EXTERNAL_IMAGE_URL] = $externalImageUrl;
    }
}
