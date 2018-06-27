<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business;

interface ProductDiscontinuedProductLabelConnectorFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function installProductDiscontinuedProductLabelConnector(): void;

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
    public function updateAbstractProductWithDiscontinuedLabel(int $idProduct): void;

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel(int $idProduct): void;
}
