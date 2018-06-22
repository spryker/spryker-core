<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Business;

interface ProductAlternativeProductLabelConnectorFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function installProductAlternativeProductLabelConnector(): void;

    /**
     * @api
     *
     * @return array
     */
    public function findAllLabels(): array;

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function updateAbstractProductWithAlternativesAvailableLabel(int $idProduct): void;
}
