<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativePublisher;

interface ProductReplacementPublisherInterface
{
    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void;
}
