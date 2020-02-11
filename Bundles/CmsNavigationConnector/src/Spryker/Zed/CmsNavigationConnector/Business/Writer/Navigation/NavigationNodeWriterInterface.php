<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Business\Writer\Navigation;

interface NavigationNodeWriterInterface
{
    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deleteNavigationNodesByIdCmsPage(int $idCmsPage): void;
}
