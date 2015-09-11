<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;

use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequestExporter;

class Name extends AbstractRequestExporter
{

    const SEX_MALE = 'M';
    const SEX_FEMALE = 'F';

    /**
     * @var string
     */
    protected $family;

    /**
     * @var string
     */
    protected $given;

    /**
     * @var string
     */
    protected $birthdate;

    /**
     * @var string
     */
    protected $sex;

    /**
     * @var string
     */
    protected $title;

    /**
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * @param string $family
     */
    public function setFamily($family)
    {
        $this->family = $family;
    }

    /**
     * @return string
     */
    public function getGiven()
    {
        return $this->given;
    }

    /**
     * @param string $given
     */
    public function setGiven($given)
    {
        $this->given = $given;
    }

    /**
     * @return string
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param string $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

}
