<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\ProductConcreteReader;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;

interface ProductConcreteReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer);
}
