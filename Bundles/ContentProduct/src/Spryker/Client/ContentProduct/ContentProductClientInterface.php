<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

interface ContentProductClientInterface
{
    /**
     * Specification:
     * - Finds content item in the key-value storage.
     * - Resolves content type and executes data.
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ExecutedProductAbstractListTransfer|null
     */
    public function getExecutedProductAbstractListById(int $idContent, string $localeName): ?ExecutedProductAbstractListTransfer;
}
