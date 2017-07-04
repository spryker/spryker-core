<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Business\Mapper;

interface CmsProductSkuParameterMapperInterface
{

    /**
     * @param array $skuList
     *
     * @return array
     */
    public function mapProductSkuList(array $skuList);

}
