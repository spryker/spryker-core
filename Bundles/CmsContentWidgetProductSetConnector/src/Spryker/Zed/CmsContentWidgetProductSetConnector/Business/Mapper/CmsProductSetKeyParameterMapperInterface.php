<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Business\Mapper;

interface CmsProductSetKeyParameterMapperInterface
{

    /**
     * @param array $keyList
     *
     * @return array
     */
    public function mapProductSetKeyList(array $keyList);

}
