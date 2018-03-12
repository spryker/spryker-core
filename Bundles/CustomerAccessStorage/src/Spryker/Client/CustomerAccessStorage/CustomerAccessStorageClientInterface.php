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
     * @param string $content
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContent($content);
}