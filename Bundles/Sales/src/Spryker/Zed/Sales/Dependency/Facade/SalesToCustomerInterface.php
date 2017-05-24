<?php


namespace Spryker\Zed\Sales\Dependency\Facade;


interface SalesToCustomerInterface
{
    /**
     * @param $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerByReference($customerReference);
}