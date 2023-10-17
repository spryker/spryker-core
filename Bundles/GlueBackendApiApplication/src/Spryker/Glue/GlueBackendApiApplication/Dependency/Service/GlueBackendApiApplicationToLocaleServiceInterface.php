<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Dependency\Service;

use Generated\Shared\Transfer\AcceptLanguageTransfer;

interface GlueBackendApiApplicationToLocaleServiceInterface
{
    /**
     * @param string $header
     * @param array<int, string> $priorities
     * @param bool $strict
     *
     * @return \Generated\Shared\Transfer\AcceptLanguageTransfer|null
     */
    public function getAcceptLanguage(string $header, array $priorities, bool $strict = false): ?AcceptLanguageTransfer;
}
