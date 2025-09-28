<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Asset\Quote;

interface QuoteStorageStrategyProviderInterface
{
    public function provideStorage(): QuoteStorageStrategyInterface;
}
