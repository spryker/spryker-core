<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Dependency\Plugin;

interface CmsPageTableExpanderPluginInterface
{

    /**
     * Specification:
     * - Retrieves a collection of ButtonTransfer transfer objects.
     *
     * @api
     *
     * @param array $cmsPage
     *
     * @return array
     */
    public function getViewButtonGroupPermanentItems(array $cmsPage);

}
