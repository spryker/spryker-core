<?php

namespace Spryker\Client\CustomerAccessStorage\Storage;

interface CustomerAccessStorageReaderInterface
{
    /**
     * @param $contentType
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContentType($contentType);
}