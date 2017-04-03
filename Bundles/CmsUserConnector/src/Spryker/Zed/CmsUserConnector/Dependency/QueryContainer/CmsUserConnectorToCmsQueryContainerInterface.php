<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Dependency\QueryContainer;

use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;

Interface CmsUserConnectorToCmsQueryContainerInterface
{
    /**
     * @param int $idCmsVersion
     *
     * @return SpyCmsVersionQuery
     */
    public function queryCmsVersionById($idCmsVersion);
}
