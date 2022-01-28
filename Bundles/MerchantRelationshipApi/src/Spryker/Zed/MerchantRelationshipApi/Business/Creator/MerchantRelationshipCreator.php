<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\MerchantRelationshipApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipApi\Business\Filter\MerchantRelationshipRequestFilterInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class MerchantRelationshipCreator implements MerchantRelationshipCreatorInterface
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
     * @var \Spryker\Zed\MerchantRelationshipApi\Business\Filter\MerchantRelationshipRequestFilterInterface
     */
    protected $merchantRelationshipRequestFilter;

    /**
     * @var \Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface
     */
    protected $merchantRelationshipMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface $apiFacade
     * @param \Spryker\Zed\MerchantRelationshipApi\Business\Filter\MerchantRelationshipRequestFilterInterface $merchantRelationshipRequestFilter
     * @param \Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface $merchantRelationshipMapper
     */
    public function __construct(
        MerchantRelationshipApiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipApiToApiFacadeInterface $apiFacade,
        MerchantRelationshipRequestFilterInterface $merchantRelationshipRequestFilter,
        MerchantRelationshipMapperInterface $merchantRelationshipMapper
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->apiFacade = $apiFacade;
        $this->merchantRelationshipRequestFilter = $merchantRelationshipRequestFilter;
        $this->merchantRelationshipMapper = $merchantRelationshipMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createMerchantRelationship(ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        $apiDataTransfer = $this->merchantRelationshipRequestFilter->filterOutDisallowedProperties($apiDataTransfer);
        $merchantRelationshipTransfer = $this->merchantRelationshipMapper->mapApiDataTransferToMerchantRelationshipTransfer(
            $apiDataTransfer,
            new MerchantRelationshipTransfer(),
        );
        $merchantRelationshipRequestTransfer = $this->createMerchantRelationshipRequestTransfer($merchantRelationshipTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer */
        $merchantRelationshipResponseTransfer = $this->merchantRelationshipFacade->createMerchantRelationship(
            new MerchantRelationshipTransfer(),
            $merchantRelationshipRequestTransfer,
        );

        if (!$merchantRelationshipResponseTransfer->getIsSuccessful()) {
            $apiValidationErrorTransfers = $this->merchantRelationshipMapper->mapMerchantRelationshipResponseTransferToApiValidationErrorTransfers(
                $merchantRelationshipResponseTransfer,
                new ArrayObject(),
            );

            return $this->getValidationErrorsApiItem(Response::HTTP_UNPROCESSABLE_ENTITY, $apiValidationErrorTransfers);
        }

        $idMerchantRelationship = $merchantRelationshipResponseTransfer->getMerchantRelationshipOrFail()->getIdMerchantRelationshipOrFail();

        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer($idMerchantRelationship);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        /** @var \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer */
        $merchantRelationshipTransfer = $merchantRelationshipCollectionTransfer->getMerchantRelationships()->offsetGet(0);
        $merchantRelationshipApiTransfer = $this->merchantRelationshipMapper->mapMerchantRelationshipTransferToMerchantRelationshipApiTransfer(
            $merchantRelationshipTransfer,
            new MerchantRelationshipApiTransfer(),
        );

        return $this->apiFacade->createApiItem(
            $merchantRelationshipApiTransfer,
            (string)$merchantRelationshipApiTransfer->getIdMerchantRelationship(),
        );
    }

    /**
     * @param int $statusCode
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     * @param string|null $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function getValidationErrorsApiItem(
        int $statusCode,
        ArrayObject $apiValidationErrorTransfers,
        ?string $idMerchantRelationship = null
    ): ApiItemTransfer {
        $apiItem = $this->apiFacade->createApiItem(
            new MerchantRelationshipApiTransfer(),
            $idMerchantRelationship,
        );

        return $apiItem
            ->setStatusCode($statusCode)
            ->setValidationErrors($apiValidationErrorTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer
     */
    protected function createMerchantRelationshipRequestTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipRequestTransfer {
        return (new MerchantRelationshipRequestTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer);
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function createMerchantRelationshipCriteriaTransfer(int $idMerchantRelationship): MerchantRelationshipCriteriaTransfer
    {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdMerchantRelationship($idMerchantRelationship);

        return (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);
    }
}
