<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class PersonalContainer extends AbstractContainer
{

    /**
     * Merchant's customer ID (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     *
     * @var string
     */
    protected $customerid;
    /**
     * PAYONE debtor ID
     *
     * @var int
     */
    protected $userid;
    /**
     * @var string
     */
    protected $salutation;
    /**
     * @var string
     */
    protected $title;
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
    protected $addressaddition;
    /**
     * @var string
     */
    protected $zip;
    /**
     * @var string
     */
    protected $city;
    /**
     * Country (ISO-3166)
     *
     * @var string
     */
    protected $country;
    /**
     * @var string
     */
    protected $state;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $telephonenumber;
    /**
     * Date of birth (YYYYMMDD)
     *
     * @var int
     */
    protected $birthday;
    /**
     * Language indicator (ISO639)
     *
     * @var string
     */
    protected $language;
    /**
     * @var string
     */
    protected $vatid;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var string
     */
    protected $personalid;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @param string $addressaddition
     */
    public function setAddressAddition($addressaddition)
    {
        $this->addressaddition = $addressaddition;
    }

    /**
     * @return null|string
     */
    public function getAddressAddition()
    {
        return $this->addressaddition;
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
     * @return null|string
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
     * @return null|string
     */
    public function getCompany()
    {
        return $this->company;
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
     * @param string $customerid
     */
    public function setCustomerId($customerid)
    {
        $this->customerid = $customerid;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerid;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $firstname
     */
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return null|string
     */
    public function getIp()
    {
        return $this->ip;
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
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * @param string $salutation
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * @return null|string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return null|string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return null|string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $telephonenumber
     */
    public function setTelephoneNumber($telephonenumber)
    {
        $this->telephonenumber = $telephonenumber;
    }

    /**
     * @return null|string
     */
    public function getTelephoneNumber()
    {
        return $this->telephonenumber;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $userid
     */
    public function setUserId($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * @param string $vatid
     */
    public function setVatId($vatid)
    {
        $this->vatid = $vatid;
    }

    /**
     * @return string
     */
    public function getVatId()
    {
        return $this->vatid;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $personalid
     */
    public function setPersonalid($personalid)
    {
        $this->personalid = $personalid;
    }

    /**
     * @return string
     */
    public function getPersonalid()
    {
        return $this->personalid;
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
