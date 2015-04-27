<?php

namespace SprykerEngine\Shared\Dto;

class LocaleDto
{
    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @return int
     */
    public function getIdLocale()
    {
        return $this->idLocale;
    }

    /**
     * @param int $idLocale
     *
     * @return $this
     */
    public function setIdLocale($idLocale)
    {
        $this->idLocale = $idLocale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocaleName()
    {
        return $this->localeName;
    }

    /**
     * @param string $localeName
     *
     * @return $this
     */
    public function setLocaleName($localeName)
    {
        $this->localeName = $localeName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }
}
