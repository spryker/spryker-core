<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use Symfony\Component\Translation\MessageSelector;

class Translator extends \Symfony\Component\Translation\Translator
{

    /**
     * Constructor.
     *
     * @param string $locale The locale
     * @param TranslationFileFinder $fileFinder
     * @param TranslationFileLoaderFactory $fileLoaderFactory
     * @param MessageSelector|null $selector The message selector for pluralization
     *
     * @throws \InvalidArgumentException If a locale contains invalid characters
     *
     * @api
     */
    public function __construct(
        $locale,
        TranslationFileFinder $fileFinder,
        TranslationFileLoaderFactory $fileLoaderFactory,
        MessageSelector $selector = null
    ) {
        parent::__construct($locale, $selector);

        foreach ($fileFinder->getTranslationFilePaths() as $path) {
            $pathParts = explode(DIRECTORY_SEPARATOR, $path);

            list($locale, $format) = explode('.', array_pop($pathParts));

            if (!$this->hasLoader($format)) {
                $this->addLoader(
                    $format,
                    $fileLoaderFactory->getLoader($format)
                );
            }

            $this->addResource($format, $path, $locale);
        }
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        $translation = parent::trans($id, $parameters, $domain, $locale);

        if ($translation === $id && strpos($id, '.') !== false && $this->has(substr($id, strpos($id, '.') + 1))) {
            $translation = parent::trans(
                substr($id, strpos($id, '.') + 1),
                $parameters,
                $domain,
                $locale
            );
        }

        return $translation;
    }

    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null)
    {
        $translation = parent::transChoice($id, $number, $parameters, $domain, $locale);

        if ($translation === $id && strpos($id, '.') !== false && $this->has(substr($id, strpos($id, '.') + 1))) {
            $translation = parent::transChoice(
                substr($id, strpos($id, '.') + 1),
                $number,
                $parameters,
                $domain,
                $locale
            );
        }

        return $translation;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->catalogues[$this->locale]->has($id);
    }

    /**
     * @param string $format
     *
     * @return bool
     */
    public function hasLoader($format)
    {
        return array_key_exists($format, $this->getLoaders());
    }

}
