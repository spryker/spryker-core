<?php

namespace Spryker\Zed\CustomerAccessStorage\Business;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageBusinessFactory getFactory()
 */
class CustomerAccessStorageFacade extends AbstractFacade implements CustomerAccessStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function publish()
    {
        $this->getFactory()->createCustomerAccessStorage()->publish();
    }
}
