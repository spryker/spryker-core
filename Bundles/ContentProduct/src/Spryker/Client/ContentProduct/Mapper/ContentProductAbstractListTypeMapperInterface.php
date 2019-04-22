<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Mapper;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;

interface ContentProductAbstractListTypeMapperInterface
{
    /**
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer|null
     */
    public function executeProductAbstractListTypeById(int $idContent, string $localeName): ?ContentProductAbstractListTypeTransfer;
}
