<?php


namespace SprykerFeature\Shared\Cms\Transfer;


use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Page extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idCmsPage;

    /**
     * @var int
     */
    protected $fkTemplate;

    /**
     * @var \DateTime
     */
    protected $validFrom;

    /**
     * @var \DateTime
     */
    protected $validTo;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @return int
     */
    public function getIdCmsPage()
    {
        return $this->idCmsPage;
    }

    /**
     * @param int $idCmsPage
     *
     * @return $this
     */
    public function setIdCmsPage($idCmsPage)
    {
        $this->addModifiedProperty('idCmsPage');
        $this->idCmsPage = $idCmsPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getFkTemplate()
    {
        return $this->fkTemplate;
    }

    /**
     * @param int $fkTemplate
     *
     * @return $this
     */
    public function setFkTemplate($fkTemplate)
    {
        $this->addModifiedProperty('fkTemplate');
        $this->fkTemplate = $fkTemplate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTime $validFrom
     *
     * @return $this
     */
    public function setValidFrom($validFrom)
    {
        $this->addModifiedProperty('validFrom');
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidTo()
    {
        return $this->validTo;
    }

    /**
     * @param \DateTime $validTo
     *
     * @return $this
     */
    public function setValidTo($validTo)
    {
        $this->addModifiedProperty('validTo');
        $this->validTo = $validTo;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
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
        $this->addModifiedProperty('isActive');
        $this->isActive = $isActive;

        return $this;
    }
}
