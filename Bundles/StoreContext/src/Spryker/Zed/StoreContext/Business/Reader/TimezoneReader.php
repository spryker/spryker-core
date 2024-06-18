<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Reader;

class TimezoneReader implements TimezoneReaderInterface
{
    /**
     * @return array<string>
     */
    public function getAvailableTimezones(): array
    {
        return timezone_identifiers_list();
    }
}
