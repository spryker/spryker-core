<?php

namespace Spryker\Client\CustomerAccessStorage\Storage;

interface CustomerAccessStorageReaderInterface
{
    /**
     * @param string $content
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContent($content);
}