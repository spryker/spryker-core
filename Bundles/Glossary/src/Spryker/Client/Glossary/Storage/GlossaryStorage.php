<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class GlossaryStorage implements GlossaryStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
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
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
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

        return str_replace(array_keys($parameters), array_values($parameters), $this->translations[$keyName]);
    }

    /**
     * @param string $keyName
     *
     * @return void
     */
    protected function loadTranslation($keyName)
    {
        $key = $this->keyBuilder->generateKey($keyName, $this->localeName);
        $this->addTranslation($keyName, $this->storage->get($key));
    }

    /**
     * @param string $keyName
     * @param string $translation
     *
     * @return void
     */
    protected function addTranslation($keyName, $translation)
    {
        if ($translation === null) {
            $translation = $keyName;
        }
        $this->translations[$keyName] = $translation;
    }

}
