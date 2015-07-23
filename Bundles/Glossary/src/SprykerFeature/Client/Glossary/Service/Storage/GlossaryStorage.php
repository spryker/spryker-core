<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service\Storage;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class GlossaryStorage implements GlossaryStorageInterface
{

    /**
     * @var StorageClientInterface
     */
    private $storage;

    /**
     * @var KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $translations = [];

    /**
     * @param StorageClientInterface $storage
     * @param KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct($storage, $keyBuilder, $localeName)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->locale = $localeName;
    }

    /**
     * @param $keyName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($keyName, array $parameters = [])
    {
        if (!isset($this->translations[$keyName])) {
            $this->loadTranslation($keyName);
        }

        if (!isset($this->translations[$keyName])) {
            return $keyName;
        }

        return str_replace(array_keys($parameters), array_values($parameters), $this->translations[$keyName]);
    }

    /**
     * @param string $keyName
     */
    private function loadTranslation($keyName)
    {
        $key = $this->keyBuilder->generateKey($keyName, $this->locale);
        $this->translations[$keyName] = $this->storage->get($key);
    }

}
