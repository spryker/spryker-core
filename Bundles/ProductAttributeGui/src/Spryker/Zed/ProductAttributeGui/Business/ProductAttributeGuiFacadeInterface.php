<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business;

interface ProductAttributeGuiFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getAttributes($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractAttributeValues($idProductAbstract);

}
