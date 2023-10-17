<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Locale;

use Generated\Shared\Transfer\AcceptLanguageTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Locale\LocaleServiceFactory getFactory()
 */
class LocaleService extends AbstractService implements LocaleServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $acceptLanguageHeader
     * @param array<int, string> $priorities
     * @param bool $strict
     *
     * @return \Generated\Shared\Transfer\AcceptLanguageTransfer|null
     */
    public function getAcceptLanguage(string $acceptLanguageHeader, array $priorities, bool $strict = false): ?AcceptLanguageTransfer
    {
        return $this->getFactory()
            ->createAcceptLanguageNegotiator()
            ->getAcceptLanguage($acceptLanguageHeader, $priorities, $strict);
    }
}
