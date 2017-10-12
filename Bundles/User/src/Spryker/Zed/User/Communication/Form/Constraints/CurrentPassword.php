<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Form\Constraints;

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
    protected $userFacade;

    /**
     * @return \Spryker\Zed\User\Business\UserFacade
     */
    public function getFacadeUser()
    {
        return $this->userFacade;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
