<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\CmsPageStore;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageStoreDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPageKeyToIdCmsPageStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCmsPageCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsPageKey = $dataSet[CmsPageStoreDataSet::KEY_PAGE_NAME];
        if (!isset($this->idCmsPageCache[$cmsPageKey])) {
            $cmsPageQuery = new SpyCmsPageQuery();
            $idCmsPage = $cmsPageQuery
                ->select(SpyCmsPageTableMap::COL_ID_CMS_PAGE)
                ->findOneByPageKey($cmsPageKey);

            if (!$idCmsPage) {
                throw new EntityNotFoundException(sprintf('Could not find cms page by page key "%s"', $cmsPageKey));
            }

            $this->idCmsPageCache[$cmsPageKey] = $idCmsPage;
        }

        $dataSet[CmsPageStoreDataSet::ID_CMS_PAGE] = $this->idCmsPageCache[$cmsPageKey];
    }
}
