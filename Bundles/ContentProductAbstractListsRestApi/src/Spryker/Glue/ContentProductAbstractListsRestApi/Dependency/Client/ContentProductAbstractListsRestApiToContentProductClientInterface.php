<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ExecutedProductAbstractListTransfer;

interface ContentProductAbstractListsRestApiToContentProductClientInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Generated\Shared\Transfer\ExecutedProductAbstractListTransfer|null
     */
    public function getExecutedProductAbstractListById(int $idContent, string $localeName): ?ExecutedProductAbstractListTransfer;
}
