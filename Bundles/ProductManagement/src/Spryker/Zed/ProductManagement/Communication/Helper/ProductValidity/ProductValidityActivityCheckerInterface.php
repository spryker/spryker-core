<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper\ProductValidity;

interface ProductValidityActivityCheckerInterface
{
    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    public function getActivationMessage($idProductConcrete): string;

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    public function getDeactivationMessage($idProductConcrete): string;
}
