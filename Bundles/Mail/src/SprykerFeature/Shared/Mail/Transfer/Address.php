<?php 

namespace SprykerFeature\Shared\Mail\Transfer;

/**
 *
 */
class Address extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $countryName = null;

    /**
     * @param string $countryName
     * @return $this
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
        $this->addModifiedProperty('countryName');
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }


}
