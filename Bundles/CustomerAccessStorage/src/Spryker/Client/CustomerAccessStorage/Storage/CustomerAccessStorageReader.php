<?php

namespace Spryker\Client\CustomerAccessStorage\Storage;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface;
use Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;

class CustomerAccessStorageReader implements CustomerAccessStorageReaderInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @param \Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface $synchronizationService
     */
    public function __construct(CustomerAccessStorageToStorageClientInterface $storageClient, CustomerAccessStorageToSynchronizationServiceInterface $synchronizationService)
    {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
    }

    /**
     * @return string
     */
    protected function generateKey()
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        return $this->synchronizationService->getStorageKeyBuilder(CustomerAccessStorageConstants::CUSTOMER_ACCESS_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess()
    {
        $unauthenticatedCustomerAccess = $this->storageClient->get($this->generateKey());

        $customerAccessTransfer = new CustomerAccessTransfer();
        if ($unauthenticatedCustomerAccess === null) {
            return $customerAccessTransfer;
        }

        return $customerAccessTransfer->fromArray($unauthenticatedCustomerAccess, true);
    }

    /**
     * @param string $content
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContent($content)
    {
        $unauthenticatedCustomerAccess = $this->storageClient->get($this->generateKey());

        if($unauthenticatedCustomerAccess === null) {
            return true;
        }


    }
}
