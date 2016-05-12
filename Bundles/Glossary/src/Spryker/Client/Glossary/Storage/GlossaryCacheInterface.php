<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary\Storage;

interface GlossaryCacheInterface
{

    /**
     * @param array $translations
     * @param int|null $ttl
     *
     * @return void
     */
    public function saveCache(array $translations, $ttl = null);

    /**
     * @return array
     */
    public function loadCache();

}
