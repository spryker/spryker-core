<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;


use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

class Customer extends AbstractRequest
{
    /**
     * @var Name
     */
    protected $name;

    /**
     * @var Address
     */
    protected $address;

    /**
     * @var Contact
     */
    protected $contact;
}
