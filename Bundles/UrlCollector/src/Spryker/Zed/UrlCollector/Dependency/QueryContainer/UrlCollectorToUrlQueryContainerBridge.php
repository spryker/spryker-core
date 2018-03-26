<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlCollector\Dependency\QueryContainer;

class UrlCollectorToUrlQueryContainerBridge implements UrlCollectorToUrlQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @param \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer
     */
    public function __construct($urlQueryContainer)
    {
        $this->urlQueryContainer = $urlQueryContainer;
    }

    /**
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds)
    {
        return $this->urlQueryContainer->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds);
    }
}
