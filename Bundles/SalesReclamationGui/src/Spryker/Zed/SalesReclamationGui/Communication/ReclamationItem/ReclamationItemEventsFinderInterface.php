<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem;

use ArrayObject;

interface ReclamationItemEventsFinderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ReclamationItemTransfer> $reclamationItems
     * @param array<array<string>> $eventsGroupedByItem
     *
     * @return array<string>
     */
    public function getDistinctManualEventsByReclamationItems(
        ArrayObject $reclamationItems,
        array $eventsGroupedByItem
    );
}
