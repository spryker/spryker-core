<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerAccess\Business\CustomerAccessBusinessFactory getFactory()
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessRepositoryInterface getRepository()
 */
class CustomerAccessFacade extends AbstractFacade implements CustomerAccessFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnrestrictedContentTypes(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessReader()->getUnrestrictedContentTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessReader()->getAllContentTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessUpdater()->updateUnauthenticatedCustomerAccess($customerAccessTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getRestrictedContentTypes(): CustomerAccessTransfer
    {
        return $this->getFactory()->createCustomerAccessReader()->getRestrictedContentTypes();
    }
}
