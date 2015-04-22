<?php

namespace SprykerFeature\Shared\Customer\Transfer;

use DateTime;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Customer extends AbstractTransfer
{

    /** @var string */
    protected $email = null;

    protected $_emailFixture = array(
        'email'
    );

    /** @var int */
    protected $idCustomer = null;

    protected $_idCustomerFixture = array(
        'integer'
    );

    /** @var int */
    protected $incrementId = null;

    protected $_incrementIdFixture = array(
        'integerBetween'
    );

    /** @var string */
    protected $firstName = null;

    protected $_firstNameFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $lastName = null;

    protected $_lastNameFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $middleName = null;

    protected $_middleNameFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $company = null;

    protected $_companyFixture = array(
        'stringUcFirst'
    );

    /** @var string */
    protected $gender = null;

    /** @var string */
    protected $_genderFixture = array(
        'gender'
    );

    /** @var DateTime */
    protected $dateOfBirth = null;

    protected $_dateOfBirthFixture = array(
        'date'
    );

    /** @var string */
    protected $salutation = null;

    protected $_salutationFixture = array(
        'salutation'
    );

    /** @var string */
    protected $password = null;

    protected $_passwordFixture = array(
        'hash'
    );

    /** @var string */
    protected $newPassword = null;

    protected $_newPasswordFixture = array(
        'hash'
    );

    /** @var string */
    protected $billingAddress = 'Customer\\Address';

    /** @var string */
    protected $shippingAddress = 'Customer\\Address';

    /** @var string */
    protected $addresses = 'Customer\\AddressCollection';

    protected $defaultBillingAddress = null;

    protected $defaultShippingAddress = null;

    /** @var DateTime */
    protected $createdAt = null;

    /** @var DateTime */
    protected $updatedAt = null;

    /** @var string */
    protected $restorePasswordKey = null;

    protected $_restorePasswordKeyFixture = array(
        'hash'
    );

    /** @var DateTime */
    protected $restorePasswordDate = null;

    /** @var string */
    protected $registrationKey = null;

    /** @var DateTime */
    protected $registered = null;

    /** @var string */
    protected $message = null;

    /**
     * @var array
     */
    protected $enrichAbleProperties = array(
        'billingAddress' => '\\SprykerFeature\\Shared\\Customer\\Transfer\\Address',
        'shippingAddress' => '\\SprykerFeature\\Shared\\Customer\\Transfer\\Address',
        'addresses' => '\\SprykerFeature\\Shared\\Customer\\Transfer\\AddressCollection'
    );

    /**
     * @param string $email
     *
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
     * @param int $idCustomer
     *
     * @return $this
     */
    public function setIdCustomer($idCustomer)
    {
        $this->idCustomer = $idCustomer;
        $this->addModifiedProperty('idCustomer');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCustomer()
    {
        return $this->idCustomer;
    }

    /**
     * @param string $incrementId
     *
     * @return $this
     */
    public function setIncrementId($incrementId)
    {
        $this->incrementId = $incrementId;
        $this->addModifiedProperty('incrementId');
        return $this;
    }

    /**
     * @return string
     */
    public function getIncrementId()
    {
        return $this->incrementId;
    }

    /**
     * @param string $firstName
     *
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
     * @param string $lastName
     *
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
     * @param string $middleName
     *
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
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        $this->addModifiedProperty('gender');
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $dateOfBirth
     *
     * @return $this
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        $this->addModifiedProperty('dateOfBirth');
        return $this;
    }

    /**
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
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
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        $this->addModifiedProperty('password');
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $newPassword
     *
     * @return $this
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
        $this->addModifiedProperty('newPassword');
        return $this;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param Address $billingAddress
     *
     * @return $this
     */
    public function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        $this->addModifiedProperty('billingAddress');
        return $this;
    }

    /**
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        $this->addModifiedProperty('shippingAddress');
        return $this;
    }

    /**
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param AddressCollection $addresses
     *
     * @return $this
     */
    public function setAddresses(AddressCollection $addresses)
    {
        $this->addresses = $addresses;
        $this->addModifiedProperty('addresses');
        return $this;
    }

    /**
     * @return Address[]|AddressCollection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param Address $address
     *
     * @return AddressCollection
     */
    public function addAddress(Address $address)
    {
        $this->addresses->add($address);
        return $this;
    }

    /**
     * @param Address $address
     *
     * @return AddressCollection
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->remove($address);
        return $this;
    }

    /**
     * @param string $defaultBillingAddress
     *
     * @return $this
     */
    public function setDefaultBillingAddress($defaultBillingAddress)
    {
        $this->defaultBillingAddress = $defaultBillingAddress;
        $this->addModifiedProperty('defaultBillingAddress');
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultBillingAddress()
    {
        return $this->defaultBillingAddress;
    }

    /**
     * @param string $defaultShippingAddress
     *
     * @return $this
     */
    public function setDefaultShippingAddress($defaultShippingAddress)
    {
        $this->defaultShippingAddress = $defaultShippingAddress;
        $this->addModifiedProperty('defaultShippingAddress');
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultShippingAddress()
    {
        return $this->defaultShippingAddress;
    }

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        $this->addModifiedProperty('createdAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        $this->addModifiedProperty('updatedAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $restorePasswordKey
     *
     * @return $this
     */
    public function setRestorePasswordKey($restorePasswordKey)
    {
        $this->restorePasswordKey = $restorePasswordKey;
        $this->addModifiedProperty('restorePasswordKey');
        return $this;
    }

    /**
     * @return string
     */
    public function getRestorePasswordKey()
    {
        return $this->restorePasswordKey;
    }

    /**
     * @param string $restorePasswordDate
     *
     * @return $this
     */
    public function setRestorePasswordDate($restorePasswordDate)
    {
        $this->restorePasswordDate = $restorePasswordDate;
        $this->addModifiedProperty('restorePasswordDate');
        return $this;
    }

    /**
     * @return string
     */
    public function getRestorePasswordDate()
    {
        return $this->restorePasswordDate;
    }

    /**
     * @param string $registrationKey
     *
     * @return $this
     */
    public function setRegistrationKey($registrationKey)
    {
        $this->registrationKey = $registrationKey;
        $this->addModifiedProperty('registrationKey');
        return $this;
    }

    /**
     * @return string
     */
    public function getRegistrationKey()
    {
        return $this->registrationKey;
    }

    /**
     * @param DateTime $registered
     *
     * @return Customer
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
        $this->addModifiedProperty('registered');
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        $this->addModifiedProperty('message');
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getEnrichAbleProperties()
    {
        return $this->enrichAbleProperties;
    }
}
