<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Dependency\Facade;

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
interface ProductLabelSearchToProductLabelInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findLabelIdsByIdProductAbstract($idProductAbstract);
}
