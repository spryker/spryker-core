<?php

namespace Spryker\Zed\CustomerAccess\Business\Model;

interface CustomerAccessCreatorInterface
{

    /**
     * @param string $contentType
     * @param bool $canAccess
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess($contentType, $canAccess);
}