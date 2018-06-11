<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Dependency\Facade;

use Generated\Shared\Transfer\ProductSuggestionDetailsTransfer;

interface ProductAlternativeToProductFacadeInterface
{
    /**
     * @param string $suggestion
     *
     * @return \Generated\Shared\Transfer\ProductSuggestionDetailsTransfer
     */
    public function getSuggestionDetails(string $suggestion): ProductSuggestionDetailsTransfer;

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract);

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProductConcrete);
}
