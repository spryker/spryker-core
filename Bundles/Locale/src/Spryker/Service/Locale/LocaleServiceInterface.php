<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Locale;

use Generated\Shared\Transfer\AcceptLanguageTransfer;

interface LocaleServiceInterface
{
    /**
     * Specification:
     * - Returns the negotiated language ISO code based on the specified accept language and priorities.
     *
     * @api
     *
     * @param string $acceptLanguageHeader
     * @param array<int, string> $priorities
     * @param bool $strict
     *
     * @return \Generated\Shared\Transfer\AcceptLanguageTransfer|null
     */
    public function getAcceptLanguage(string $acceptLanguageHeader, array $priorities, bool $strict = false): ?AcceptLanguageTransfer;
}
