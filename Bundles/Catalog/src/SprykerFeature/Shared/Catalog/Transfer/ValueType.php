<?php 

namespace SprykerFeature\Shared\Catalog\Transfer;

/**
 *
 */
class ValueType extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idCatalogValueType = null;

    protected $variety = null;

    protected $fkCatalogAttribute = null;

    protected $fkCatalogAttributeSet = null;

    /**
     * @param int $idCatalogValueType
     * @return $this
     */
    public function setIdCatalogValueType($idCatalogValueType)
    {
        $this->idCatalogValueType = $idCatalogValueType;
        $this->addModifiedProperty('idCatalogValueType');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCatalogValueType()
    {
        return $this->idCatalogValueType;
    }

    /**
     * @param string $variety
     * @return $this
     */
    public function setVariety($variety)
    {
        $this->variety = $variety;
        $this->addModifiedProperty('variety');
        return $this;
    }

    /**
     * @return string
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * @param int $fkCatalogAttribute
     * @return $this
     */
    public function setFkCatalogAttribute($fkCatalogAttribute)
    {
        $this->fkCatalogAttribute = $fkCatalogAttribute;
        $this->addModifiedProperty('fkCatalogAttribute');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCatalogAttribute()
    {
        return $this->fkCatalogAttribute;
    }

    /**
     * @param int $fkCatalogAttributeSet
     * @return $this
     */
    public function setFkCatalogAttributeSet($fkCatalogAttributeSet)
    {
        $this->fkCatalogAttributeSet = $fkCatalogAttributeSet;
        $this->addModifiedProperty('fkCatalogAttributeSet');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCatalogAttributeSet()
    {
        return $this->fkCatalogAttributeSet;
    }


}
