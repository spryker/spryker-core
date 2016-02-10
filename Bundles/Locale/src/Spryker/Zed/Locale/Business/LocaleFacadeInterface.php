<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

interface LocaleFacadeInterface
{

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName);

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * @return string
     */
    public function getCurrentLocaleName();

    /**
     * @return array
     */
    public function getAvailableLocales();

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\LocaleExistsException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function createLocale($localeName);

    /**
     * @param string $localeName
     *
     * @return void
     */
    public function deleteLocale($localeName);

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger);

}
