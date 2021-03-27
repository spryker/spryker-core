<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserPasswordResetMail\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMailFacadeInterface;
use Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantUserPasswordResetMail\MerchantUserPasswordResetMailConfig getConfig()
 */
class MerchantUserPasswordResetMailCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMailFacadeInterface
     */
    public function getMailFacade(): MerchantUserPasswordResetMailToMailFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserPasswordResetMailDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\MerchantUserPasswordResetMail\Dependency\Facade\MerchantUserPasswordResetMailToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantUserPasswordResetMailToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserPasswordResetMailDependencyProvider::FACADE_MERCHANT_USER);
    }
}
