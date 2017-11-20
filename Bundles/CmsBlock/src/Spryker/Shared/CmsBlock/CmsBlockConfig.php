<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlock;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CmsBlockConfig extends AbstractSharedConfig
{
    /**
     * Specification
     * - Defines the collector resource name
     *
     * @api
     */
    const RESOURCE_TYPE_CMS_BLOCK = 'cms-block';

    /**
     * @return array
     */
    public function getTest()
    {
        return [
            1
        ];
    }
}
