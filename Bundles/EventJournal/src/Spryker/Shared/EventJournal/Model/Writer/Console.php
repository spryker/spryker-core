<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Writer;

use Spryker\Shared\EventJournal\Model\EventInterface;

/**
 * @deprecated Use Log bundle instead
 */
class Console extends AbstractWriter
{
    public const TYPE = 'console';

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return bool
     */
    public function write(EventInterface $event)
    {
        echo json_encode($event->getFields(), JSON_PRETTY_PRINT);

        return true;
    }
}
