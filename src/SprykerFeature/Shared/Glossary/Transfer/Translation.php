<?php

namespace SprykerFeature\Shared\Glossary\Transfer;


use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Translation extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idGlossaryTranslation = null;

    /**
     * @var int
     */
    protected $fkGlossaryKey;

    /**
     * @var int
     */
    protected $fkLocale;

    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var bool
     */
    protected $isActive = true;

    /**
     * @return int
     */
    public function getFkGlossaryKey()
    {
        return $this->fkGlossaryKey;
    }

    /**
     * @param $fkGlossaryKey
     * @return $this
     */
    public function setFkGlossaryKey($fkGlossaryKey)
    {
        $this->addModifiedProperty('fkGlossaryKey');
        $this->fkGlossaryKey = $fkGlossaryKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkLocale()
    {
        return $this->fkLocale;
    }

    /**
     * @param $fkLocale
     * @return $this
     */
    public function setFkLocale($fkLocale)
    {
        $this->addModifiedProperty('fkLocale');
        $this->fkLocale = $fkLocale;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->addModifiedProperty('value');
        $this->value = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->addModifiedProperty('isActive');
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdGlossaryTranslation()
    {
        return $this->idGlossaryTranslation;
    }

    /**
     * @param $idGlossaryTranslation
     * @return $this
     */
    public function setIdGlossaryTranslation($idGlossaryTranslation)
    {
        $this->addModifiedProperty('idGlossaryTranslation');
        $this->idGlossaryTranslation = $idGlossaryTranslation;

        return $this;
    }


}
