<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

interface NewsletterToGlossaryInterface
{

    /**
     * @param string $keyName
     * @param LocaleTransfer|null $locale
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $locale = null);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale);

}
