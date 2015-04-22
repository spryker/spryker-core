<?php 

namespace SprykerFeature\Shared\Catalog\Transfer;

/**
 *
 */
class AttributeSetGroup extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idCatalogAttributeSetGroup = null;

    protected $fkCatalogGroup = null;

    protected $fkCatalogValueType = null;

    /**
     * @param int $idCatalogAttributeSetGroup
     * @return $this
     */
    public function setIdCatalogAttributeSetGroup($idCatalogAttributeSetGroup)
    {
        $this->idCatalogAttributeSetGroup = $idCatalogAttributeSetGroup;
        $this->addModifiedProperty('idCatalogAttributeSetGroup');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCatalogAttributeSetGroup()
    {
        return $this->idCatalogAttributeSetGroup;
    }

    /**
     * @param int $fkCatalogGroup
     * @return $this
     */
    public function setFkCatalogGroup($fkCatalogGroup)
    {
        $this->fkCatalogGroup = $fkCatalogGroup;
        $this->addModifiedProperty('fkCatalogGroup');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCatalogGroup()
    {
        return $this->fkCatalogGroup;
    }

    /**
     * @param int $fkCatalogValueType
     * @return $this
     */
    public function setFkCatalogValueType($fkCatalogValueType)
    {
        $this->fkCatalogValueType = $fkCatalogValueType;
        $this->addModifiedProperty('fkCatalogValueType');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCatalogValueType()
    {
        return $this->fkCatalogValueType;
    }


}
