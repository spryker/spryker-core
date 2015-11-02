<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

interface GlossaryClientInterface
{

    /**
     * @param string $id
     * @param array $parameters
     * @param string $localeName
     *
     * @return string
     */
    public function translate($id, array $parameters, $localeName);

}
