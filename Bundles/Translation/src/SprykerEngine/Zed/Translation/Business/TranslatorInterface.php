<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Translation\Business;

use Symfony\Component\Translation\MessageSelector;

interface TranslatorInterface extends \Symfony\Component\Translation\TranslatorInterface
{

    /**
     * Constructor.
     *
     * @param string $locale   The locale
     * @param MessageSelector|null $selector The message selector for pluralization
     * @param TranslationFileFinder $fileFinder
     * @param TranslationFileLoaderFactory $fileLoaderFactory
     *
     * @throws \InvalidArgumentException If a locale contains invalid characters
     *
     * @api
     */
    public function __construct(
        $locale,
        MessageSelector $selector = null,
        TranslationFileFinder $fileFinder,
        TranslationFileLoaderFactory $fileLoaderFactory
    );

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id);

}
