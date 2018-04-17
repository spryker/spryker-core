<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\Dependency\Client;

interface CurrencyToZedRequestClientInterface
{
    /**
     * Specification:
     *  - Get messages from zed request and put them to session in next order:
     *  - Writes error message to flash bag.
     *  - Writes success message to flash bag.
     *  - Writes informational message to flash bag.
     *
     * @api
     *
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();
}
