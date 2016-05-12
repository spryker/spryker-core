<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Glossary\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class GlossaryStorageCache implements GlossaryCacheInterface
{

    const CACHE_KEY_PREFIX = 'cache:';

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\Glossary\KeyBuilder\GlossaryKeyBuilder
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $cacheKey
     * @param string $localeName
     */
    public function __construct(StorageClientInterface $storageClient, KeyBuilderInterface $keyBuilder, $cacheKey, $localeName)
    {
        $this->storageClient = $storageClient;
        $this->keyBuilder = $keyBuilder;
        $this->cacheKey = $cacheKey;
        $this->localeName = $localeName;
    }

    /**
     * @param array $translations
     * @param int|null $ttl
     *
     * @return void
     */
    public function saveCache(array $translations, $ttl = null)
    {
        if (!empty($translations)) {
            $this->storageClient->set($this->getCacheKey(), json_encode(array_keys($translations)), $ttl);
        }
    }

    /**
     * @return array
     */
    public function loadCache()
    {
        $translations = [];
        $translationKeys = $this->storageClient->get($this->getCacheKey());
        if (!empty($translationKeys)) {
            $keyMap = $this->buildKeyMap($translationKeys, $this->localeName);

            $translationsByKey = $this->storageClient->getMulti(array_keys($keyMap));
            foreach ($translationsByKey as $key => $translation) {
                $translations[$keyMap[$key]] = $translation;
            }
        }

        return $translations;
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->keyBuilder->generateKey(self::CACHE_KEY_PREFIX . $this->cacheKey, $this->localeName);
    }

    /**
     * @param string $translationKeys
     * @param string $localeName
     *
     * @return mixed
     */
    protected function buildKeyMap($translationKeys, $localeName)
    {
        $keyMap = [];

        foreach ($translationKeys as $key) {
            $transformedKey = $this->keyBuilder->generateKey($key, $localeName);
            $keyMap[$transformedKey] = $key;
        }

        return $keyMap;
    }

}
