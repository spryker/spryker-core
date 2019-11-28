<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

interface ProductOptionStorageReaderInterface
{
    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductOptionsByProductAbstractSkus(
        array $productAbstractSkus,
        string $localeName,
        array $sorts
    ): array;

    /**
     * @param string $productConcreteSku
     *
     * @return int[]
     */
    public function getProductOptionIdsByProductConcreteSku(string $productConcreteSku): array;

    /**
     * @param string[] $productConcreteSkus
     * @param string $localeName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductOptionsByProductConcreteSkus(
        array $productConcreteSkus,
        string $localeName,
        array $sorts
    ): array;
}
