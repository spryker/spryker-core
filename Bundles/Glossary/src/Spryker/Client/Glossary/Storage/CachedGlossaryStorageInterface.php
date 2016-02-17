<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary\Storage;

interface CachedGlossaryStorageInterface extends GlossaryStorageInterface
{

    /**
     * @param int $ttl
     *
     * @return void
     */
    public function saveCache($ttl);

}
