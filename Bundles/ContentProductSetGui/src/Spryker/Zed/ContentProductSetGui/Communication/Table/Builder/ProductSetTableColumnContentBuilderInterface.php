<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Table\Builder;

use Orm\Zed\ProductSet\Persistence\SpyProductSet;

interface ProductSetTableColumnContentBuilderInterface
{
    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    public function getDeleteButton(SpyProductSet $productSetEntity): string;

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    public function getAddButtonField(SpyProductSet $productSetEntity): string;
}
