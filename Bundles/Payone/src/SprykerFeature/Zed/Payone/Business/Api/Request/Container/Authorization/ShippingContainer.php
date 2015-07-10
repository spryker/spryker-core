<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class ShippingContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $shipping_firstname;
    /**
     * @var string
     */
    protected $shipping_lastname;
    /**
     * @var string
     */
    protected $shipping_company;
    /**
     * @var string
     */
    protected $shipping_street;
    /**
     * @var string
     */
    protected $shipping_zip;
    /**
     * @var string
     */
    protected $shipping_city;
    /**
     * ISO-3166-2 Subdivisions
     * only necessary for country US, CA, CN, JP, MX, BR, AR, ID, TH, IN
     *
     * @var string
     */
    protected $shipping_state;
    /**
     * Country (ISO-3166)
     *
     * @var string
     */
    protected $shipping_country;

    /**
     * @param string $shipping_city
     */
    public function setShippingCity($shipping_city)
    {
        $this->shipping_city = $shipping_city;
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->shipping_city;
    }

    /**
     * @param string $shipping_company
     */
    public function setShippingCompany($shipping_company)
    {
        $this->shipping_company = $shipping_company;
    }

    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->shipping_company;
    }

    /**
     * @param string $shipping_country
     */
    public function setShippingCountry($shipping_country)
    {
        $this->shipping_country = $shipping_country;
    }

    /**
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->shipping_country;
    }

    /**
     * @param string $shipping_firstname
     */
    public function setShippingFirstName($shipping_firstname)
    {
        $this->shipping_firstname = $shipping_firstname;
    }

    /**
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shipping_lastname
     */
    public function setShippingLastName($shipping_lastname)
    {
        $this->shipping_lastname = $shipping_lastname;
    }

    /**
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->shipping_lastname;
    }

    /**
     * @param string $shipping_state
     */
    public function setShippingState($shipping_state)
    {
        $this->shipping_state = $shipping_state;
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->shipping_state;
    }

    /**
     * @param string $shipping_street
     */
    public function setShippingStreet($shipping_street)
    {
        $this->shipping_street = $shipping_street;
    }

    /**
     * @return string
     */
    public function getShippingStreet()
    {
        return $this->shipping_street;
    }

    /**
     * @param string $shipping_zip
     */
    public function setShippingZip($shipping_zip)
    {
        $this->shipping_zip = $shipping_zip;
    }

    /**
     * @return string
     */
    public function getShippingZip()
    {
        return $this->shipping_zip;
    }

}
