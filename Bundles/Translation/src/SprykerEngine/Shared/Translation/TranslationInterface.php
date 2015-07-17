<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Translation;

use Generated\Shared\Transfer\LocaleTransfer;

interface TranslationInterface
{

    /**
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param LocaleTransfer $locale
     *
     * @return string
     */
    public function translate($id, array $parameters = [], $domain = null, LocaleTransfer $locale = null);

    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string $domain
     * @param LocaleTransfer $locale
     *
     * @return string
     */
    public function translateChoice($id, $number, array $parameters = [], $domain = null, LocaleTransfer $locale = null);

}
