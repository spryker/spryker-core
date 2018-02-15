<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetChartConnector\Business\Mapper;

class CmsChartKeyMapperPlugin implements CmsChartKeyMapperPluginInterface
{
    /**
     * @param array $keyList
     *
     * @return array
     */
    public function mapChartKeyList(array $keyList)
    {
        return $keyList;
    }
}
