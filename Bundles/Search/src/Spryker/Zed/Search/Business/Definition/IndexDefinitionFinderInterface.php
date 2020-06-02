<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Definition;

interface IndexDefinitionFinderInterface
{
    /**
     * @return \Generated\Shared\Transfer\IndexDefinitionFileTransfer[]
     */
    public function getSortedIndexDefinitionFileTransfers(): array;
}
