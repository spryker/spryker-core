<?php

namespace SprykerEngine\Zed\Translation\Business\Model;

class TranslationFile implements TranslationFileInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $format;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return TranslationFileInterface
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return TranslationFileInterface
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return TranslationFileInterface
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}