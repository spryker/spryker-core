<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\DataProvider;

interface ProductRelationDataProviderInterface
{
    /**
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    public function buildTemplateData(array $parameters);

    /**
     * @return string
     */
    public function getAcceptedType();
}
