<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Glossary\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Symfony\Contracts\Translation\TranslatorTrait;

class GlossaryStorage implements GlossaryStorageInterface
{
    use TranslatorTrait;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct(StorageClientInterface $storage, KeyBuilderInterface $keyBuilder, $localeName)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->localeName = $localeName;
    }

    /**
     * @param string $keyName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($keyName, array $parameters = [])
    {
        if ((string)$keyName === '') {
            return $keyName;
        }

        if (!isset($this->translations[$keyName])) {
            $this->loadTranslation($keyName);
        }

        if (empty($parameters)) {
            return $this->translations[$keyName];
        }

        return $this->trans($this->translations[$keyName], $parameters, $this->localeName);
    }

    /**
     * @param string $keyName
     *
     * @return void
     */
    protected function loadTranslation($keyName): void
    {
        $key = $this->keyBuilder->generateKey($keyName, $this->localeName);
        $this->addTranslation($keyName, $this->storage->get($key));
    }

    /**
     * @param string $keyName
     * @param string|null $translation
     *
     * @return void
     */
    protected function addTranslation($keyName, $translation): void
    {
        if ($translation === null) {
            $translation = $keyName;
        }
        $this->translations[$keyName] = $translation;
    }
}
