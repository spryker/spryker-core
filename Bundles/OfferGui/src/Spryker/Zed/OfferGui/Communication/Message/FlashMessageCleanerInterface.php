<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Message;

interface FlashMessageCleanerInterface
{
    /**
     * @return void
     */
    public function clearDuplicateMessages(): void;
}
