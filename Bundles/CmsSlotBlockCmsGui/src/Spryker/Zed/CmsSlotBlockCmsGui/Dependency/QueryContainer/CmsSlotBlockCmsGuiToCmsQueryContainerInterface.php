<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer;

use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;

interface CmsSlotBlockCmsGuiToCmsQueryContainerInterface
{
    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryPagesByLocale(int $idLocale): SpyCmsPageQuery;
}
