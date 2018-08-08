<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Attribute;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;

interface AttributeMapInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array
     */
    public function generateAttributeMap($idProductAbstract, $idLocale);

    /**
     * @param int[] $idsProductAbstract
     * @param int[] $idsLocale
     *
     * @return array
     */
    public function generateAttributeMapBulk(array $idsProductAbstract, array $idsLocale): array;

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param array $attributeMapBulk
     *
     * @return \Generated\Shared\Transfer\AttributeMapStorageTransfer
     */
    public function getConcreteProductsFromBulk(int $idProductAbstract, int $idLocale, array $attributeMapBulk): AttributeMapStorageTransfer;
}
