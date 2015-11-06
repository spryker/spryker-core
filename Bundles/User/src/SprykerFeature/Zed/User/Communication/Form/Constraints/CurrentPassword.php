<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Form\Constraints;

use SprykerFeature\Zed\User\Business\UserFacade;
use Symfony\Component\Validator\Constraint;

class CurrentPassword extends Constraint
{

    /**
     * @var string
     */
    protected $message = 'Incorrect current password provided.';

    /**
     * @var UserFacade
     */
    protected $facadeUser;

    /**
     * @return UserFacade
     */
    public function getFacadeUser()
    {
        return $this->facadeUser;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

}
