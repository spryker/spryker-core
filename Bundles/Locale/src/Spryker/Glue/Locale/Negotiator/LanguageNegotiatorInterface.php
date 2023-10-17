<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Locale\Negotiator;

interface LanguageNegotiatorInterface
{
    /**
     * @param string|null $headerAcceptLanguage
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getLanguageIsoCode(?string $headerAcceptLanguage): string;
}
