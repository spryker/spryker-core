<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel;

interface ProductLabelClientInterface
{

    /**
     * Specification:
     * - Finds product labels for the given abstract-product in the key-value storage
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName);

}
