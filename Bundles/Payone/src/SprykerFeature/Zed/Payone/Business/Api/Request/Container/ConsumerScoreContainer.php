<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

class ConsumerScoreContainer extends AbstractRequestContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_CONSUMERSCORE;

    /**
     * @var int
     */
    protected $aid;
    /**
     * @var string
     */
    protected $addresschecktype;
    /**
     * @var string
     */
    protected $consumerscoretype;
    /**
     * @var string
     */
    protected $firstname;
    /**
     * @var string
     */
    protected $lastname;
    /**
     * @var string
     */
    protected $company;
    /**
     * @var string
     */
    protected $street;
    /**
     * @var string
     */
    protected $streetname;
    /**
     * @var string
     */
    protected $streetnumber;
    /**
     * @var string
     */
    protected $zip;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var string
     */
    protected $country;
    /**
     * @var string
     */
    protected $birthday;
    /**
     * @var string
     */
    protected $telephonenumber;
    /**
     * @var string
     */
    protected $language;

    /**
     * @param string $addresschecktype
     */
    public function setAddressCheckType($addresschecktype)
    {
        $this->addresschecktype = $addresschecktype;
    }

    /**
     * @return string
     */
    public function getAddressCheckType()
    {
        return $this->addresschecktype;
    }

    /**
     * @param int $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $consumerscoretype
     */
    public function setConsumerScoreType($consumerscoretype)
    {
        $this->consumerscoretype = $consumerscoretype;
    }

    /**
     * @return string
     */
    public function getConsumerScoreType()
    {
        return $this->consumerscoretype;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $firstname
     */
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $lastname
     */
    public function setLastName($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $streetname
     */
    public function setStreetName($streetname)
    {
        $this->streetname = $streetname;
    }

    /**
     * @return string
     */
    public function getStreetName()
    {
        return $this->streetname;
    }

    /**
     * @param string $streetnumber
     */
    public function setStreetNumber($streetnumber)
    {
        $this->streetnumber = $streetnumber;
    }

    /**
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetnumber;
    }

    /**
     * @param string $telephonenumber
     */
    public function setTelephoneNumber($telephonenumber)
    {
        $this->telephonenumber = $telephonenumber;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephonenumber;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

}
