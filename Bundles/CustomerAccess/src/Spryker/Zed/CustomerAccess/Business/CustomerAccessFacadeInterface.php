<?php

namespace Spryker\Zed\CustomerAccess\Business;

interface CustomerAccessFacadeInterface
{
    /**
     * Specification:
     * - Installs the necessary data for access of unauthenticated customers from config
     *
     * @api
     *
     * @return void
     */
    public function install();

    /**
     * Specification
     * - Returns the configured content types along with their access combined with the database entries in the CustomerAccessTransfer
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess();
}