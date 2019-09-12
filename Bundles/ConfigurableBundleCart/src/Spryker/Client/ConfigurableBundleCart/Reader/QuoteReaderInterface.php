<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Reader;

interface QuoteReaderInterface
{
    /**
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsByConfiguredBundleGroupKey(string $groupKey): array;
}
