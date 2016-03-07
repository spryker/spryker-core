<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Filter;

use Spryker\Shared\EventJournal\Model\EventInterface;

interface FilterInterface
{

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function filter(EventInterface $event);

    /**
     * @return string
     */
    public function getType();

}
