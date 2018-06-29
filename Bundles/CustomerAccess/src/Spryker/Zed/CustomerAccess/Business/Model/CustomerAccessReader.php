<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessRepositoryInterface;

class CustomerAccessReader implements CustomerAccessReaderInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessRepositoryInterface
     */
    protected $customerAccessRepository;

    /**
     * @param \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessRepositoryInterface $customerAccessRepository
     */
    public function __construct(CustomerAccessRepositoryInterface $customerAccessRepository)
    {
        $this->customerAccessRepository = $customerAccessRepository;
    }

    /**
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer|null
     */
    public function findCustomerAccessByContentType(string $contentType): ?ContentTypeAccessTransfer
    {
        return $this->customerAccessRepository->findCustomerAccessByContentType($contentType);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getContentTypesWithUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        return $this->customerAccessRepository->getContentTypesWithUnauthenticatedCustomerAccess();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer
    {
        return $this->customerAccessRepository->getAllContentTypes();
    }
}
