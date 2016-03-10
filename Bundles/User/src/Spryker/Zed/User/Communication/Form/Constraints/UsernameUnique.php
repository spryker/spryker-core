<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form\Constraints;

use Symfony\Component\Validator\Constraint;

class UsernameUnique extends Constraint
{

    /**
     * @var string
     */
    public $message = 'A user with username {{ username }} already exists!';

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $facadeUser;

    /**
     * @var string
     */
    protected $username;

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

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

}
