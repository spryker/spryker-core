<?php

namespace SprykerFeature\Shared\Category\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class CategoryNode extends AbstractTransfer
{

    /**
     * @var bool
     */
    protected $isRoot = null;

    /**
     * @var int
     */
    protected $idCategoryNode = null;

    /**
     * @var int
     */
    protected $fkCategory = null;

    /**
     * @var int
     */
    protected $fkParentCategoryNode = null;

    /**
     * @return bool
     */
    public function getIsRoot()
    {
        return $this->isRoot;
    }

    /**
     * @param bool $isRoot
     * @return $this
     */
    public function setIsRoot($isRoot)
    {
        $this->isRoot = $isRoot;
        $this->addModifiedProperty('isRoot');

        return $this;
    }

    /**
     * @return int
     */
    public function getIdCategoryNode()
    {
        return $this->idCategoryNode;
    }

    /**
     * @param int $idCategoryNode
     * @return $this
     */
    public function setIdCategoryNode($idCategoryNode)
    {
        $this->idCategoryNode = $idCategoryNode;
        $this->addModifiedProperty('idCategoryNode');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkCategory()
    {
        return $this->fkCategory;
    }

    /**
     * @param int $fkCategory
     * @return $this
     */
    public function setFkCategory($fkCategory)
    {
        $this->fkCategory = $fkCategory;
        $this->addModifiedProperty('fkCategory');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkParentCategoryNode()
    {
        return $this->fkParentCategoryNode;
    }

    /**
     * @param int $fkParentCategoryNode
     * @return $this
     */
    public function setFkParentCategoryNode($fkParentCategoryNode)
    {
        $this->fkParentCategoryNode = $fkParentCategoryNode;
        $this->addModifiedProperty('fkParentCategoryNode');

        return $this;
    }
}
