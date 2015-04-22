<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Dependency\Facade;

use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface GlossaryToLocaleInterface
{
    /**
     * @param string $localeName
     * @return int
     * @throws MissingLocaleException
     */
    public function getIdLocale($localeName);

    /**
     * @return string
     */
    public function getCurrentLocale();

    /**
     * @return int
     */
    public function getCurrentIdLocale();

    /**
     * @return array
     */
    public function getRelevantLocales();
}
