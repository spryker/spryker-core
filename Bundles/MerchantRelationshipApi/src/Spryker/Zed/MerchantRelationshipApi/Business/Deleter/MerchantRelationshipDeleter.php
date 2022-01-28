<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Deleter;

use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class MerchantRelationshipDeleter implements MerchantRelationshipDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface
     */
    protected $apiFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface $apiFacade
     */
    public function __construct(
        MerchantRelationshipApiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipApiToApiFacadeInterface $apiFacade
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->apiFacade = $apiFacade;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function delete(int $idMerchantRelationship): ApiItemTransfer
    {
        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())->setIdMerchantRelationship($idMerchantRelationship);
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())->setMerchantRelationship($merchantRelationshipTransfer);

        $this->merchantRelationshipFacade->deleteMerchantRelationship(
            $merchantRelationshipTransfer,
            $merchantRelationshipRequestTransfer,
        );

        return $this->apiFacade
            ->createApiItem(new MerchantRelationshipTransfer())
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
