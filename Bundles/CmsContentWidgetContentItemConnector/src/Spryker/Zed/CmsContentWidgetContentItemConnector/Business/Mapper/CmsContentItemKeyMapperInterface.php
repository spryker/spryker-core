<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper;

interface CmsContentItemKeyMapperInterface
{
    /**
     * @param array<string> $keyList
     *
     * @return array<string, string>
     */
    public function mapContentItemKeyList(array $keyList): array;
}
