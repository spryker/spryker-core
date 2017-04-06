<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Collector\Storage;

use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Collector\Business\Collector\Search\AbstractSearchPropelCollector;

class CmsPageCollector extends AbstractSearchPropelCollector
{

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        // TODO: remove this debug output
        dump($touchKey,$collectItemData);
//        dump(json_decode($collectItemData['spy_cms_versiondata'], true));die;
        return [
            'url' => '',
            'valid_from' => '',
            'valid_to' => '',
            'is_active' => 1,
            'id' => 1,
            'template' => '',
            'placeholders' => '',
            'name' => '',
            'meta_title' => '',
            'meta_keywords' => '',
            'meta_description' => '',
        ];
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_PAGE;
    }

}
