<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface GlossaryToLocaleInterface
{
    /**
     * @param string $localeName
     *
     * @return LocaleTransfer
     * @throws MissingLocaleException
     */
    public function getLocale($localeName);

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * @return array
     */
    public function getRelevantLocaleNames();
}
