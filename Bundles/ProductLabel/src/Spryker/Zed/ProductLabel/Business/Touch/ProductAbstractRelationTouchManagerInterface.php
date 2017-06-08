<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Touch;

interface ProductAbstractRelationTouchManagerInterface
{

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchActiveByIdProductAbstract($idProductAbstract);

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchDeletedByIdProductAbstract($idProductAbstract);

}
