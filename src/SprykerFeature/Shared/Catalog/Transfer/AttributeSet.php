<?php 

namespace SprykerFeature\Shared\Catalog\Transfer;

/**
 *
 */
class AttributeSet extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idCatalogAttributeSet = null;

    protected $name = null;

    /**
     * @param int $idCatalogAttributeSet
     * @return $this
     */
    public function setIdCatalogAttributeSet($idCatalogAttributeSet)
    {
        $this->idCatalogAttributeSet = $idCatalogAttributeSet;
        $this->addModifiedProperty('idCatalogAttributeSet');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCatalogAttributeSet()
    {
        return $this->idCatalogAttributeSet;
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
