<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Form\DataProvider;

interface ProductRelationTypeDataProviderInterface
{
    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param int|null $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function getData($idProductRelation = null);
}
