<?php

namespace Spryker\Client\CustomerAccessStorage;

interface CustomerAccessStorageClientInterface
{
    /**
     * Specification:
     * - Checks if unauthenticated customer can access specific kind of content
     *
     * @api
     *
     * @param $contentType
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContentType($contentType);
}