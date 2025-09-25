<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Service\UtilDateTime\Model;

interface TimezoneReaderInterface
{
    /**
     * @return string
     */
    public function getTimeZone(): string;
}
