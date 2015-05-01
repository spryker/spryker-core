<?php

namespace SprykerFeature\Shared\Cms\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class PageKeyMapping extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idCmsGlossaryKeyMapping;

    /**
     * @var int
     */
    protected $fkPage;

    /**
     * @var int
     */
    protected $fkGlossaryKey;

    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @return int
     */
    public function getIdCmsGlossaryKeyMapping()
    {
        return $this->idCmsGlossaryKeyMapping;
    }

    /**
     * @param int $idCmsGlossaryKeyMapping
     *
     * @return $this
     */
    public function setIdCmsGlossaryKeyMapping($idCmsGlossaryKeyMapping)
    {
        $this->addModifiedProperty('idCmsGlossaryKeyMapping');
        $this->idCmsGlossaryKeyMapping = $idCmsGlossaryKeyMapping;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkPage()
    {
        return $this->fkPage;
    }

    /**
     * @param int $fkPage
     *
     * @return $this
     */
    public function setFkPage($fkPage)
    {
        $this->addModifiedProperty('fkPage');
        $this->fkPage = $fkPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkGlossaryKey()
    {
        return $this->fkGlossaryKey;
    }

    /**
     * @param int $fkGlossaryKey
     *
     * @return $this
     */
    public function setFkGlossaryKey($fkGlossaryKey)
    {
        $this->addModifiedProperty('fkGlossaryKey');
        $this->fkGlossaryKey = $fkGlossaryKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->addModifiedProperty('placeholder');
        $this->placeholder = $placeholder;

        return $this;
    }
}
