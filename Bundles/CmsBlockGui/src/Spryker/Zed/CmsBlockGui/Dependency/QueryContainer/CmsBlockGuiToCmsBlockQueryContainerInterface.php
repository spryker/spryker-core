<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Dependency\QueryContainer;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;

interface CmsBlockGuiToCmsBlockQueryContainerInterface
{
    /**
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name): SpyCmsBlockQuery;

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate(): SpyCmsBlockQuery;

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates(): SpyCmsBlockTemplateQuery;

    /**
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock): SpyCmsBlockStoreQuery;
}
