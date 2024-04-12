<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Psr\Log\LoggerInterface;

interface MergerInterface
{
    /**
     * @param array<array> $transferDefinitions
     * @param \Psr\Log\LoggerInterface|null $messenger
     *
     * @return array<string, array>
     */
    public function merge(array $transferDefinitions, ?LoggerInterface $messenger = null): array;
}
