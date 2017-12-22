<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Business\Model;

interface NavigationNodesIsActiveUpdaterInterface
{
    /**
     * @param int $idCmsPage
     * @param bool $isActive
     *
     * @return void
     */
    public function updateCmsPageNavigationNodes($idCmsPage, $isActive);
}
