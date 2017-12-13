<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage;

use Spryker\Client\CmsStorage\Mapper\CmsPageStorageMapper;
use Spryker\Client\Kernel\AbstractFactory;

class CmsStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsStorage\Mapper\CmsPageStorageMapperInterface
     */
    public function createCmsPageStorageMapper()
    {
        return new CmsPageStorageMapper();
    }
}
