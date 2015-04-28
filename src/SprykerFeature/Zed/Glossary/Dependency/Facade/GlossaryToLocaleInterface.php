<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Dependency\Facade;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface GlossaryToLocaleInterface
{
    /**
     * @param string $localeName
     *
     * @return LocaleDto
     * @throws MissingLocaleException
     */
    public function getLocale($localeName);

    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();

    /**
     * @return array
     */
    public function getRelevantLocaleNames();
}
