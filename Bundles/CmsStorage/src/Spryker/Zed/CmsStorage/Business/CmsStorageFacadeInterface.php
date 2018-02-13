<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Business;

interface CmsStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds);

    /**
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds);
}
