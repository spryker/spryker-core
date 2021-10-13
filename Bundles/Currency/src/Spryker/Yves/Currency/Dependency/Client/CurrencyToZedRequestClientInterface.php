<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\Dependency\Client;

interface CurrencyToZedRequestClientInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseErrorMessages();

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseSuccessMessages();

    /**
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesErrorMessages(): array;

    /**
     * @api
     *
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesSuccessMessages(): array;
}
