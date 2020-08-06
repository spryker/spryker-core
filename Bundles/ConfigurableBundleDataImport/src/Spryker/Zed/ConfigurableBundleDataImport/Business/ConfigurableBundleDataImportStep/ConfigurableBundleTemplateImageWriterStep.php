<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Spryker\Zed\ConfigurableBundle\Dependency\ConfigurableBundleEvents;
use Spryker\Zed\ConfigurableBundleDataImport\Business\DataSet\ConfigurableBundleTemplateImageDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ConfigurableBundleTemplateImageWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productImageSetKey = $dataSet[ConfigurableBundleTemplateImageDataSetInterface::COLUMN_PRODUCT_IMAGE_SET_KEY];

        $productImageSetEntity = $this->createProductImageSetQuery()
            ->filterByProductImageSetKey($productImageSetKey)
            ->findOne();

        if (!$productImageSetEntity) {
            throw new EntityNotFoundException(sprintf('Could not find product image set by key "%s"', $productImageSetKey));
        }

        $productImageSetEntity
            ->setFkResourceConfigurableBundleTemplate($dataSet[ConfigurableBundleTemplateImageDataSetInterface::ID_CONFIGURABLE_BUNDLE_TEMPLATE])
            ->save();

        $this->addPublishEvents(
            ConfigurableBundleEvents::CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_PUBLISH,
            $productImageSetEntity->getFkResourceConfigurableBundleTemplate()
        );
    }

    /**
     * @module ProductImage
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function createProductImageSetQuery(): SpyProductImageSetQuery
    {
        return SpyProductImageSetQuery::create();
    }
}
