<?php

namespace Spryker\Zed\CustomerAccess\Business;

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
        return $this->getFactory()->createCustomerAccessReader()->findUnauthenticatedCustomerAccess();
    }
}