<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CmsBlockProductStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery
     */
    public function queryCmsBlockProductStorageByIds(array $productIds);

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return $this|\Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProducts(array $productIds);

    /**
     * @api
     *
     * @param array $cmsBlockProductIds
     *
     * @return $this|\Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductsByIds(array $cmsBlockProductIds);
}
