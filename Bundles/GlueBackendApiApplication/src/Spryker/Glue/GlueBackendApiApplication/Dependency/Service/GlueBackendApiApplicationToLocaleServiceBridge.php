<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Dependency\Service;

use Generated\Shared\Transfer\AcceptLanguageTransfer;

class GlueBackendApiApplicationToLocaleServiceBridge implements GlueBackendApiApplicationToLocaleServiceInterface
{
    /**
     * @var \Spryker\Service\Locale\LocaleServiceInterface
     */
    protected $localeService;

    /**
     * @param \Spryker\Service\Locale\LocaleServiceInterface $localeService
     */
    public function __construct($localeService)
    {
        $this->localeService = $localeService;
    }

    /**
     * @param string $header
     * @param array<int, string> $priorities
     * @param bool $strict
     *
     * @return \Generated\Shared\Transfer\AcceptLanguageTransfer|null
     */
    public function getAcceptLanguage(string $header, array $priorities, bool $strict = false): ?AcceptLanguageTransfer
    {
        return $this->localeService->getAcceptLanguage($header, $priorities, $strict);
    }
}
