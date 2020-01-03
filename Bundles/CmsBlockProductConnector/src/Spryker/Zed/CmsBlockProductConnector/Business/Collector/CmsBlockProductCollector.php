<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business\Collector;

use Spryker\Shared\CmsBlockProductConnector\CmsBlockProductConnectorConstants;
use Spryker\Zed\CmsBlockProductConnector\Persistence\Collector\Storage\Propel\CmsBlockProductConnectorCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsBlockProductCollector extends AbstractStoragePropelCollector
{
    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $cmsBlockNames = $this->extractCmsBlockNames($collectItemData[CmsBlockProductConnectorCollectorQuery::COL_CMS_BLOCK_NAMES]);

        return ['' => $cmsBlockNames];
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsBlockProductConnectorConstants::RESOURCE_TYPE_CMS_BLOCK_PRODUCT_CONNECTOR;
    }

    /**
     * @param string $cmsBlockNames
     *
     * @return string[]
     */
    protected function extractCmsBlockNames($cmsBlockNames)
    {
        $separator = ',';

        return explode($separator, trim($cmsBlockNames));
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }
}
