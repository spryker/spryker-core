<?php


namespace Spryker\Zed\ProductAttributeGui\Dependency\Facade;


use Generated\Shared\Transfer\LocaleTransfer;

interface ProductAttributeGuiToGlossaryInterface
{
    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function createAndTouchTranslation($keyName, LocaleTransfer $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $value
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function updateAndTouchTranslation($keyName, $locale, $value, $isActive = true);

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function hasTranslation($keyName, LocaleTransfer $localeTransfer = null);

    /**
     * @param string $keyName
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingTranslationException
     *
     * @return \Generated\Shared\Transfer\TranslationTransfer
     */
    public function getTranslation($keyName, LocaleTransfer $locale);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param string $oldKeyName
     * @param string $newKeyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\MissingKeyException
     *
     * @return bool
     */
    public function updateKey($oldKeyName, $newKeyName);

    /**
     * @param string $keyName
     *
     * @throws \Spryker\Zed\Glossary\Business\Exception\KeyExistsException
     *
     * @return int
     */
    public function createKey($keyName);

}