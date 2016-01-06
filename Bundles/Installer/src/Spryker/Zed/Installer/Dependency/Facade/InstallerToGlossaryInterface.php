<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\Glossary\Business\Exception\KeyExistsException;
use Spryker\Zed\Glossary\Business\Exception\MissingKeyException;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Spryker\Zed\Glossary\Business\Exception\TranslationExistsException;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;

interface InstallerToGlossaryInterface
{

    /**
     * @param string $keyName
     *
     * @throws KeyExistsException
     *
     * @return int
     */
    public function createKey($keyName);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

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

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingKeyException
     * @throws MissingLocaleException
     * @throws TranslationExistsException
     *
     * @return TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws MissingTranslationException
     *
     * @return TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

}
