<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Communication\Form\Constraints;

use Spryker\Zed\User\Business\UserFacade;
use Symfony\Component\Validator\Constraint;

class CurrentPassword extends Constraint
{

    /**
     * @var string
     */
    protected $message = 'Incorrect current password provided.';

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $facadeUser;

    /**
     * @return \Spryker\Zed\User\Business\UserFacade
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
