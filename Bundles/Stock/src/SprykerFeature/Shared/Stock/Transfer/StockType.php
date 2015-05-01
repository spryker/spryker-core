<?php

namespace SprykerFeature\Shared\Stock\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class StockType extends AbstractTransfer
{

    protected $idStock = null;

    protected $name = null;

    /**
     * @return int
     */
    public function getIdStock()
    {
        return $this->idStock;
    }

    /**
     * @param int $idStock
     *
     * @return $this
     */
    public function setIdStock($idStock)
    {
        $this->idStock = $idStock;
        $this->addModifiedProperty('idStock');

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
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }
}
