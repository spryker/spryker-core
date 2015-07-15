<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

interface GlossaryClientInterface
{

    /**
     * @param string $keyName
     * @param array $parameters
     * @param string $localeName
     *
     * @return string
     */
    public function translate($keyName, array $parameters = [], $localeName);

}
