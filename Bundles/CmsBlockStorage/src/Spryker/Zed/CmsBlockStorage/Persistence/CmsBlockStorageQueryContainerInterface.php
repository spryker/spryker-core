<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CmsBlockStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorageQuery
     */
    public function queryCmsBlockStorageEntities(array $cmsBlockIds);

    /**
     * @api
     *
     * @param int[] $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByIds(array $cmsBlockIds);

    /**
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryBlockWithRelationsByIds(array $cmsBlockIds);
}
