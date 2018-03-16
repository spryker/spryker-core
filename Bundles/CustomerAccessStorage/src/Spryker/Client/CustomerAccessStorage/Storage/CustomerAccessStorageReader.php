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
     * @var \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected $customerAccess;

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
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        $this->readCustomerAccess();
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach($this->customerAccess->getContentTypeAccess() as $contentTypeAccess) {
            if($contentTypeAccess->getCanAccess()) {
                $customerAccessTransfer->addContentTypeAccess($contentTypeAccess);
            }
        }

        return $customerAccessTransfer;
    }

    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        $this->readCustomerAccess();

        return $this->customerAccess;
    }

    /**
     * @return void
     */
    protected function readCustomerAccess()
    {
        if(!$this->customerAccess) {
            $unauthenticatedCustomerAccess = $this->storageClient->get($this->generateKey());

            if(is_null($unauthenticatedCustomerAccess)) {
                $unauthenticatedCustomerAccess = [];
            }

            $this->customerAccess = (new CustomerAccessTransfer())->fromArray($unauthenticatedCustomerAccess, true);
        }
    }
}
