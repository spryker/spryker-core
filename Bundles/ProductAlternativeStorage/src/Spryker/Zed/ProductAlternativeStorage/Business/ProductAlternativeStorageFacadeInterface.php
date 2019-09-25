<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business;

interface ProductAlternativeStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $idProduct
     *
     * @return void
     */
    public function publishAlternative(array $idProduct): void;

    /**
     * Specification:
     *  - Publish replacements for abstract product
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void;

    /**
     * Specification:
     *  - Publish replacements for concrete product
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void;
}
