<?php

namespace SprykerEngine\Zed\Translation\Business\Model;

interface TranslationFileInterface
{
    /**
     * @return string
     */
    public function getPath();

    /**
     * @param string $path
     * @return TranslationFileInterface
     */
    public function setPath($path);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $locale
     * @return TranslationFileInterface
     */
    public function setLocale($locale);

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @param string $format
     * @return TranslationFileInterface
     */
    public function setFormat($format);
}