<?php

namespace Spryker\Client\CustomerAccessStorage\Storage;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
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
    protected $unauthenticatedCustomerAccess;

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
     * @param string $contentType
     *
     * @return bool
     */
    public function canUnauthenticatedCustomerAccessContentType($contentType)
    {
        if(!$this->unauthenticatedCustomerAccess) {
            $unauthenticatedCustomerAccess = $this->storageClient->get($this->generateKey());

            if($unauthenticatedCustomerAccess === null) {
                return true;
            }

            $this->unauthenticatedCustomerAccess = (new CustomerAccessTransfer())->fromArray($unauthenticatedCustomerAccess, true);
        }

        foreach($this->unauthenticatedCustomerAccess->getContentTypeAccess() as $contentTypeAccess) {
            if($contentTypeAccess->getContentType() === $contentType) {
                return $contentTypeAccess->getCanAccess();
            }
        }

        return true;
    }
}
