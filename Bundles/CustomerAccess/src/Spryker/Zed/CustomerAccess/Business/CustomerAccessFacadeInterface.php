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
     * Specification:
     * - Returns the all content types that the customer can see when not logged-in in a CustomerAccessTransfer object
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess();

    /**
     * Specification:
     * - Returns all content types from the database table
     *
     * @api
     *
     * @return array
     */
    public function findAllContentTypes();

    /**
     * Specification:
     * - Updates only these content types supplied to accessible (canAccess->true)
     *
     * @api
     *
     * @param string[] $customerAccessTransfer
     *
     * @return void
     */
    public function updateOnlyContentTypesToAccessible($customerAccessTransfer);
}