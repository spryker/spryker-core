<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model\Step;

use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentKeyToIdStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $contentEntity = (new SpyContentQuery())
            ->findOneByKey($dataSet[ContentBannerDataSetInterface::CONTENT_BANNER_KEY]);

        if ($contentEntity) {
            $dataSet[ContentBannerDataSetInterface::CONTENT_BANNER_ID] = $contentEntity->getIdContent();
        }
    }
}
