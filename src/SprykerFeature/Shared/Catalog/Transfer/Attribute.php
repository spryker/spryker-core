<?php 

namespace SprykerFeature\Shared\Catalog\Transfer;

/**
 *
 */
class Attribute extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idCatalogAttribute = null;

    protected $name = null;

    /**
     * @param int $idCatalogAttribute
     * @return $this
     */
    public function setIdCatalogAttribute($idCatalogAttribute)
    {
        $this->idCatalogAttribute = $idCatalogAttribute;
        $this->addModifiedProperty('idCatalogAttribute');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCatalogAttribute()
    {
        return $this->idCatalogAttribute;
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}
