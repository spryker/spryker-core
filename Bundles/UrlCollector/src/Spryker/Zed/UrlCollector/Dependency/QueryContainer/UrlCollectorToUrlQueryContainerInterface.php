<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlCollector\Dependency\QueryContainer;

interface UrlCollectorToUrlQueryContainerInterface
{
    /**
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds);
}
