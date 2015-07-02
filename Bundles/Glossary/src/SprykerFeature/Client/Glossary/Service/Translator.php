<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class Translator
{

    /**
     * @var ReadInterface
     */
    protected $kvStorage;

    /**
     * @var KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @param ReadInterface $kvStorage
     * @param KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct($kvStorage, $keyBuilder, $localeName)
    {
        $this->kvStorage = $kvStorage;
        $this->keyBuilder = $keyBuilder;
        $this->locale = $localeName;
    }

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
    protected function loadTranslation($keyName)
    {
        $key = $this->keyBuilder->generateKey($keyName, $this->locale);
        $this->translations[$keyName] = $this->kvStorage->get($key);
    }

    public function getLocale()
    {
        return $this->locale;
    }
}

