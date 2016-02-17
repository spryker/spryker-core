<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary;

interface GlossaryClientInterface
{

    /**
     * @param string $id
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = []);

    /**
     * @param string $id
     * @param string $localeName
     * @param string $requestCacheKey
     * @param array $parameters
     *
     * @return string
     */
    public function cachedTranslate($id, $localeName, $requestCacheKey, array $parameters = []);

    /**
     * @param string $localeName
     * @param string $requestCacheKey
     * @param int|null $ttl
     *
     * @return void
     */
    public function saveCache($localeName, $requestCacheKey, $ttl = null);

}
