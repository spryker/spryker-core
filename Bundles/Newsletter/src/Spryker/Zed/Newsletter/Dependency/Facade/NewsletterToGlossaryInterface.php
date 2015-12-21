<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;

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
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale);

}
