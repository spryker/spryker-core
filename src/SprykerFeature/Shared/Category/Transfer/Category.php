<?php

namespace SprykerFeature\Shared\Category\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Category extends AbstractTransfer
{

    /**
     * @var int
     */
    protected $idCategory = null;

    /**
     * @var bool
     */
    protected $isActive = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $url = null;

    /**
     * @return int
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * @param int $idCategory
     * @return $this
     */
    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;
        $this->addModifiedProperty('idCategory');

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
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        $this->addModifiedProperty('isActive');

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }
}
