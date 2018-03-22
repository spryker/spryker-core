<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Collector;

use Spryker\Shared\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel\CmsBlockCategoryConnectorCollector;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsBlockCategoryCollector extends AbstractStoragePropelCollector
{
    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        return $this->extractCmsBlockNames($collectItemData[CmsBlockCategoryConnectorCollector::COL_POSITIONS]);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsBlockCategoryConnectorConfig::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR;
    }

    /**
     * @param string $cmsBlockNames
     *
     * @return array
     */
    protected function extractCmsBlockNames($cmsBlockNames)
    {
        $separator = ',';
        $positions = explode($separator, trim($cmsBlockNames));

        $cmsBlockNames = [];
        foreach ($positions as $position) {
            $positionCmsBlock = explode(':', $position);

            if (isset($positionCmsBlock[0], $positionCmsBlock[1])) {
                $cmsBlockNames[$positionCmsBlock[0]][] = $positionCmsBlock[1];
            }
        }

        return $cmsBlockNames;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }
}
