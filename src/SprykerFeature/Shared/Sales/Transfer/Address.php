<?php 

namespace SprykerFeature\Shared\Sales\Transfer;

/**
 *
 */
class Address extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesOrderAddress = null;

    protected $idCustomerAddress = null;

    protected $iso2Country = null;

    protected $region = null;

    protected $fkMiscCountry = null;

    protected $salutation = null;

    protected $firstName = null;

    protected $middleName = null;

    protected $lastName = null;

    protected $email = null;

    protected $address1 = null;

    protected $address2 = null;

    protected $address3 = null;

    protected $company = null;

    protected $city = null;

    protected $zipCode = null;

    protected $poBox = null;

    protected $phone = null;

    protected $cellPhone = null;

    protected $description = null;

    /**
     * @param int $idSalesOrderAddress
     * @return $this
     */
    public function setIdSalesOrderAddress($idSalesOrderAddress)
    {
        $this->idSalesOrderAddress = $idSalesOrderAddress;
        $this->addModifiedProperty('idSalesOrderAddress');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesOrderAddress()
    {
        return $this->idSalesOrderAddress;
    }

    /**
     * @param int $idCustomerAddress
     * @return $this
     */
    public function setIdCustomerAddress($idCustomerAddress)
    {
        $this->idCustomerAddress = $idCustomerAddress;
        $this->addModifiedProperty('idCustomerAddress');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCustomerAddress()
    {
        return $this->idCustomerAddress;
    }

    /**
     * @param string $iso2Country
     * @return $this
     */
    public function setIso2Country($iso2Country)
    {
        $this->iso2Country = $iso2Country;
        $this->addModifiedProperty('iso2Country');
        return $this;
    }

    /**
     * @return string
     */
    public function getIso2Country()
    {
        return $this->iso2Country;
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
        $this->addModifiedProperty('region');
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param int $fkMiscCountry
     * @return $this
     */
    public function setFkMiscCountry($fkMiscCountry)
    {
        $this->fkMiscCountry = $fkMiscCountry;
        $this->addModifiedProperty('fkMiscCountry');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkMiscCountry()
    {
        return $this->fkMiscCountry;
    }

    /**
     * @param string $salutation
     * @return $this
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
        $this->addModifiedProperty('salutation');
        return $this;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        $this->addModifiedProperty('firstName');
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $middleName
     * @return $this
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        $this->addModifiedProperty('middleName');
        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        $this->addModifiedProperty('lastName');
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->addModifiedProperty('email');
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $address1
     * @return $this
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        $this->addModifiedProperty('address1');
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        $this->addModifiedProperty('address2');
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address3
     * @return $this
     */
    public function setAddress3($address3)
    {
        $this->address3 = $address3;
        $this->addModifiedProperty('address3');
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany($company)
    {
        $this->company = $company;
        $this->addModifiedProperty('company');
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        $this->addModifiedProperty('city');
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $zipCode
     * @return $this
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        $this->addModifiedProperty('zipCode');
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $poBox
     * @return $this
     */
    public function setPoBox($poBox)
    {
        $this->poBox = $poBox;
        $this->addModifiedProperty('poBox');
        return $this;
    }

    /**
     * @return string
     */
    public function getPoBox()
    {
        return $this->poBox;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        $this->addModifiedProperty('phone');
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $cellPhone
     * @return $this
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;
        $this->addModifiedProperty('cellPhone');
        return $this;
    }

    /**
     * @return string
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->addModifiedProperty('description');
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


}
