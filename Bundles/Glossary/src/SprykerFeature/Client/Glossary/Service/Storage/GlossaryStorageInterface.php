<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service\Storage;

interface GlossaryStorageInterface
{

    /**
     * @param string $keyName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($keyName, array $parameters = []);

}
