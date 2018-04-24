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
 */
class CustomerAccessFacade extends AbstractFacade implements CustomerAccessFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()->createInstaller()->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess()
    {
        return $this->getFactory()->createCustomerAccessReader()->getUnauthenticatedCustomerAccess();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function findAllContentTypes()
    {
        return $this->getFactory()->createCustomerAccessReader()->getAllContentTypes();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return void
     */
    public function updateOnlyContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer)
    {
        $this->getFactory()->createCustomerAccessUpdater()->updateOnlyContentTypesToAccessible($customerAccessTransfer);
    }
}
