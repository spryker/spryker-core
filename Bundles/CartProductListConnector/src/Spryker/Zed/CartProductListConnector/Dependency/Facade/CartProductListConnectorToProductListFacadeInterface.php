<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartProductListConnector\Dependency\Facade;

interface CartProductListConnectorToProductListFacadeInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;
}
