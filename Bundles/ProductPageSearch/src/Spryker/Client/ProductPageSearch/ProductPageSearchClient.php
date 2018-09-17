<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPageSearch;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductPageSearch\ProductPageSearchFactory getFactory()
 */
class ProductPageSearchClient extends AbstractClient implements ProductPageSearchClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array|\Elastica\ResultSet
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteReader()
            ->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);
    }
}
