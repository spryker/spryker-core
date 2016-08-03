<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;


interface ReaderInterface
{

    /**
     * @param $idProductAbstract
     *
     * @return array
     */
    public function getProductImagesByProductAbstractId($idProductAbstract);

}
