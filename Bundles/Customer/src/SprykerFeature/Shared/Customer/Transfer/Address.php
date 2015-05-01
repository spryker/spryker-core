<?php

namespace SprykerFeature\Shared\Customer\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Address extends AbstractTransfer
{
    /** @var int */
    protected $idCustomerAddress = null;

    protected $_idCustomerAddressFixture = array(
        'integer'
    );

    /** @var int */
    protected $customerId = null;

    protected $_customerIdFixture = array(
        'integer'
    );

    /** @var int */
    protected $fkCustomer = null;

    protected $_fkCustomerFixture = array(
        'integer'
    );

    /** @var string */
    protected $email = null;

    /** @var string */
    protected $salutation = null;

    protected $_salutationFixture = array(
        'salutation'
    );

    /** @var string */
    protected $name = null;

    protected $_nameFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $address1 = null;

    protected $_address1Fixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $address2 = null;

    protected $_address2Fixture = array(
        'integer'
    );

    /** @var string */
    protected $address3 = null;

    protected $_address3Fixture = array(
        'integer'
    );

    /** @var string */
    protected $company = null;

    protected $_companyFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $city = null;

    protected $_cityFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $zipCode = null;

    protected $_zipCodeFixture = array(
        'integer'
    );

    /** @var string */
    protected $poBox = null;

    protected $_poBoxFixture = array(
        'integer'
    );

    /** @var string */
    protected $phone = null;

    protected $_phoneFixture = array(
        'integer'
    );

    /** @var string */
    protected $cellPhone = null;

    protected $_cellPhoneFixture = array(
        'integer'
    );

    /** @var string */
    protected $comment = null;

    protected $_commentFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $state = null;

    protected $_stateFixture = array(
        'stringUcFirst'
    );

    /** @var bool */
    protected $isDeleted = null;

    /** @var bool */
    protected $isDefaultBilling = null;

    /** @var bool */
    protected $isDefaultShipping = null;

    /** @var int */
    protected $fkCountry = null;

    /** @var string */
    protected $iso2Country = null;

    /**
     * @param int $idCustomerAddress
     *
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
     * @param int $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        $this->addModifiedProperty('customerId');
        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param int $fkCustomer
     *
     * @return $this
     */
    public function setFkCustomer($fkCustomer)
    {
        $this->fkCustomer = $fkCustomer;
        $this->addModifiedProperty('fkCustomer');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCustomer()
    {
        return $this->fkCustomer;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Address
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->addModifiedProperty('email');
        return $this;
    }

    /**
     * @param string $salutation
     *
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $address1
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     *
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
     * @param string $comment
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        $this->addModifiedProperty('comment');
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param bool $isDeleted
     *
     * @return $this
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
        $this->addModifiedProperty('isDeleted');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDefaultBilling
     *
     * @return $this
     */
    public function setIsDefaultBilling($isDefaultBilling)
    {
        $this->isDefaultBilling = $isDefaultBilling;
        $this->addModifiedProperty('isDefaultBilling');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDefaultBilling()
    {
        return $this->isDefaultBilling;
    }

    /**
     * @param bool $isDefaultShipping
     *
     * @return $this
     */
    public function setIsDefaultShipping($isDefaultShipping)
    {
        $this->isDefaultShipping = $isDefaultShipping;
        $this->addModifiedProperty('isDefaultShipping');
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDefaultShipping()
    {
        return $this->isDefaultShipping;
    }

    /**
     * @param int $fkCountry
     *
     * @return $this
     */
    public function setFkCountry($fkCountry)
    {
        $this->fkCountry = $fkCountry;
        $this->addModifiedProperty('fkCountry');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCountry()
    {
        return $this->fkCountry;
    }

    /**
     * @param string $iso2Country
     *
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
}
