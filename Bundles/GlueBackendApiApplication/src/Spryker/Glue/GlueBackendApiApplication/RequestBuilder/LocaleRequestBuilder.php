<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface;

class LocaleRequestBuilder implements LocaleRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface
     */
    protected $languageNegotiation;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\Language\LanguageNegotiationInterface $languageNegotiation
     */
    public function __construct(LanguageNegotiationInterface $languageNegotiation)
    {
        $this->languageNegotiation = $languageNegotiation;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        if (isset($glueRequestTransfer->getMeta()[static::HEADER_ACCEPT_LANGUAGE])) {
            $glueRequestTransfer->setLocale(
                $this->languageNegotiation->getLanguageIsoCode(current($glueRequestTransfer->getMeta()[static::HEADER_ACCEPT_LANGUAGE])),
            );
        }

        return $glueRequestTransfer;
    }
}
