<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface UrlStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $urlIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls(array $urlIds);

    /**
     * @api
     *
     * @param array $urlIds
     *
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery
     */
    public function queryUrlStorageByIds(array $urlIds);

    /**
     * @api
     *
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds);

    /**
     * @api
     *
     * @param array $redirectIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects(array $redirectIds);

    /**
     * @api
     *
     * @param array $redirectIds
     *
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorageQuery
     */
    public function queryRedirectStorageByIds(array $redirectIds);
}
