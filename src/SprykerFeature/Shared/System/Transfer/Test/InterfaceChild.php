<?php 

namespace SprykerFeature\Shared\System\Transfer\Test;

/**
 *
 */
class InterfaceChild extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer implements \PhpUnit\SprykerFeature\Shared\System\TestInterface
{

    protected $bub = null;

    /**
     * @param string $bub
     * @return $this
     */
    public function setBub($bub)
    {
        $this->bub = $bub;
        $this->addModifiedProperty('bub');
        return $this;
    }

    /**
     * @return string
     */
    public function getBub()
    {
        return $this->bub;
    }


}
