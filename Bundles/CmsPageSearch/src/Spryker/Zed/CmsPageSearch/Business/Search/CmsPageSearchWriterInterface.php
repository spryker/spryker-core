<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Business\Search;

interface CmsPageSearchWriterInterface
{
    /**
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds);

    /**
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds);
}
