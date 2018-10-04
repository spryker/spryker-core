<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider;

interface ForeignKeysProviderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ForeignKeyFileTransfer[]
     */
    public function getForeignKeyList(): array;
}
