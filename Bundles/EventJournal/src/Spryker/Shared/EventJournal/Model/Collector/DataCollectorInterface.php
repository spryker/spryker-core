<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Collector;

interface DataCollectorInterface
{

    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getType();

}
