<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Dependency\QueryContainer;

class UrlStorageToUrlQueryContainerBridge implements UrlStorageToUrlQueryContainerInterface
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
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls()
    {
        return $this->urlQueryContainer->queryUrls();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects()
    {
        return $this->urlQueryContainer->queryRedirects();
    }

    /**
     * @param string $resourceType
     * @param int[] $resourceIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds)
    {
        return $this->urlQueryContainer->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds);
    }
}
