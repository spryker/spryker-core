<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\ConfigReader;

interface GiftCardConfigReaderInterface
{
    /**
     * @return string[]
     */
    public function getGiftCardOnlyShipmentMethods(): array;

    /**
     * @deprecated Added for BC reasons, will be removed in next major release. Use getGiftCardOnlyShipmentMethods() instead.
     *
     * @return string[]
     */
    public function getGiftCardOnlyShipmentMethodsWithBC(): array;
}
