<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence\Mapper;

use Propel\Runtime\Collection\Collection;

interface ProductAbstractMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection|null $productAbstracts
     *
     * @return array
     */
    public function mapProductAbstractArrayToOptions(?Collection $productAbstracts): array;
}
