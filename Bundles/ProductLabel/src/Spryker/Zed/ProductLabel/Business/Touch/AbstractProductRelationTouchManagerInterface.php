<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Touch;

interface AbstractProductRelationTouchManagerInterface
{

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    public function touchActiveForAbstractProduct($idAbstractProduct);

    /**
     * @param int $idAbstractProduct
     *
     * @return void
     */
    public function touchDeletedForAbstractProduct($idAbstractProduct);

}
