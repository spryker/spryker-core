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
     * @var array
     */
    protected $idContentBannerCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $contentBannerKey = $dataSet[ContentBannerDataSetInterface::CONTENT_BANNER_KEY];
        if (!isset($this->idContentBannerCache[$contentBannerKey])) {
            $contentQuery = new SpyContentQuery();
            $contentEntity = $contentQuery
                ->findOneByKey($contentBannerKey);

            if ($contentEntity) {
                $this->idContentBannerCache[$contentBannerKey] = $contentEntity->getIdContent();
            }
        }

        if (isset($this->idContentBannerCache[$contentBannerKey])) {
            $dataSet[ContentBannerDataSetInterface::ID_CONTENT_BANNER] = $this->idContentBannerCache[$contentBannerKey];
        }
    }
}
