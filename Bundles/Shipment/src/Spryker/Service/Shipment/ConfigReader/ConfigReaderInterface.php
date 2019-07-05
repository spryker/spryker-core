<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\ConfigReader;

interface ConfigReaderInterface
{
    /**
     * @return string
     */
    public function getShipmentExpenseType(): string;
}
