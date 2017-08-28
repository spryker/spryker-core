<?php

namespace Spryker\Zed\CustomerGroup\Dependency\QueryContainer;


use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class CustomerGroupToCustomerQueryContainerBridge implements CustomerGroupToCustomerQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct($customerQueryContainer)
    {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomers()
    {
        return $this->customerQueryContainer->queryCustomers();
    }

}