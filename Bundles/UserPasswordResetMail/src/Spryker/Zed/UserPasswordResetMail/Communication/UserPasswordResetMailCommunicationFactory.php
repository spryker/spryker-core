<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserPasswordResetMail\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\UserPasswordResetMail\Dependency\Facade\UserPasswordResetMailToMailFacadeInterface;
use Spryker\Zed\UserPasswordResetMail\UserPasswordResetMailDependencyProvider;

/**
 * @method \Spryker\Zed\UserPasswordResetMail\UserPasswordResetMailConfig getConfig()
 */
class UserPasswordResetMailCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\UserPasswordResetMail\Dependency\Facade\UserPasswordResetMailToMailFacadeInterface
     */
    public function getMailFacade(): UserPasswordResetMailToMailFacadeInterface
    {
        return $this->getProvidedDependency(UserPasswordResetMailDependencyProvider::FACADE_MAIL);
    }
}
