<?php

namespace SprykerFeature\Yves\Glossary\Model;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerFeature\Yves\Glossary\Exception\KeyBuilderNotFoundException;
use SprykerFeature\Yves\Glossary\Exception\StorageKeyGeneratorNotFoundException;
use SprykerFeature\Sdk\Glossary\Exception\TranslationNotFoundException;
use SprykerFeature\Yves\KVStoreAware;
use Symfony\Component\Translation\TranslatorInterface;

class KVTranslator implements TranslatorInterface, KVStoreAware
{

    protected $translator;

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
    protected $locale = 'de_DE';



    /**
     * @throws StorageKeyGeneratorNotFoundException
     */
    protected function checkKVStorageHasBeenSet()
    {
        if (empty($this->kvStorage)) {
            throw new StorageKeyGeneratorNotFoundException('Tried to translate without supplying a kv implementation');
        }
    }

    /**
     * @throws StorageKeyGeneratorNotFoundException
     */
    protected function checkKeyBuilderHasBeenSet()
    {
        if (empty($this->keyBuilder)) {
            throw new KeyBuilderNotFoundException('Tried to translate without supplying a keybuilder implementation');
        }
    }

    /**
     * @param ReadInterface $kvReader
     * @return KVStoreAware
     */
    public function setKeyValueReader(ReadInterface $kvReader)
    {
        $this->kvStorage = $kvReader;

        return $this;
    }

    /**
     * @param KeyBuilderInterface $keyBuilder
     * @return $this
     */
    public function setKeyBuilder(KeyBuilderInterface $keyBuilder)
    {
        $this->keyBuilder = $keyBuilder;

        return $this;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Translates the given message.
     *
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     *
     * @return string The translated string
     *
     * @api
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $this->checkKVStorageHasBeenSet();
        $this->checkKeyBuilderHasBeenSet();

        if (!isset($this->translations[$id])) {
            $this->loadTranslation($id, $locale);
        }

        if (!isset($this->translations[$id])) {
            throw new TranslationNotFoundException($id);
        }

        return str_replace(array_keys($parameters), array_values($parameters), $this->translations[$id]);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param int $number The number to use to find the indice of the message
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     *
     * @return string The translated string
     *
     * @api
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        // TODO: Implement transChoice() method.
    }

    /**
     * Returns the current locale.
     *
     * @return string The locale
     *
     * @api
     */
    public function getLocale()
    {
        return $this->locale;
    }


}
