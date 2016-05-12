<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class CachedGlossaryStorage extends GlossaryStorage implements CachedGlossaryStorageInterface
{

    /**
     * @var \Spryker\Client\Glossary\Storage\GlossaryCacheInterface
     */
    protected $glossaryStorageCache;

    /**
     * @var bool
     */
    protected $isCacheMissed = false;

    /**
     * @param \Spryker\Client\Glossary\Storage\GlossaryCacheInterface $glossaryStorageCache
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct(GlossaryCacheInterface $glossaryStorageCache, StorageClientInterface $storage, KeyBuilderInterface $keyBuilder, $localeName)
    {
        parent::__construct($storage, $keyBuilder, $localeName);

        $this->glossaryStorageCache = $glossaryStorageCache;
        $this->warmUpCache();
    }

    /**
     * @return void
     */
    protected function warmUpCache()
    {
        $this->translations = $this->glossaryStorageCache->loadCache();
        $this->translationKeyMap = array_fill_keys(array_keys($this->translations), true);
    }

    /**
     * @param string $keyName
     *
     * @return void
     */
    protected function loadTranslation($keyName)
    {
        $this->isCacheMissed = true;

        parent::loadTranslation($keyName);
    }

    /**
     * @param int $ttl
     *
     * @return void
     */
    public function saveCache($ttl)
    {
        if (!empty($this->translations) && $this->isCacheMissed === true) {
            $this->glossaryStorageCache->saveCache($this->translations, $ttl);
        }
    }

}
