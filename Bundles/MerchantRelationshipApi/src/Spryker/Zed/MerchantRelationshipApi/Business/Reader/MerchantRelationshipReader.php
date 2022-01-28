<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Reader;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Request\MerchantRelationshipRequestDataInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface;
use Symfony\Component\HttpFoundation\Response;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
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
     * @var \Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface
     */
    protected $merchantRelationshipMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface $apiFacade
     * @param \Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface $merchantRelationshipMapper
     */
    public function __construct(
        MerchantRelationshipApiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipApiToApiFacadeInterface $apiFacade,
        MerchantRelationshipMapperInterface $merchantRelationshipMapper
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->apiFacade = $apiFacade;
        $this->merchantRelationshipMapper = $merchantRelationshipMapper;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function getMerchantRelationship(int $idMerchantRelationship): ApiItemTransfer
    {
        $merchantRelationshipCriteriaTransfer = $this->createMerchantRelationshipCriteriaTransfer($idMerchantRelationship);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        if ($merchantRelationshipCollectionTransfer->getMerchantRelationships()->count() === 0) {
            $apiValidationErrorTransfer = (new ApiValidationErrorTransfer())
                ->setField(MerchantRelationshipRequestDataInterface::KEY_ID)
                ->addMessages(sprintf('Merchant relationship is not found for id `%s`.', $idMerchantRelationship));

            return $this->createApiItemWithValidationError(
                Response::HTTP_NOT_FOUND,
                $apiValidationErrorTransfer,
                (string)$idMerchantRelationship,
            );
        }

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
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function getMerchantRelationshipCollection(ApiRequestTransfer $apiRequestTransfer): ApiCollectionTransfer
    {
        $merchantRelationshipCriteriaTransfer = $this->merchantRelationshipMapper->mapApiRequestTransferToMerchantRelationshipCriteriaTransfer(
            $apiRequestTransfer,
            new MerchantRelationshipCriteriaTransfer(),
        );

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->merchantRelationshipFacade->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        $apiCollectionTransfer = $this->merchantRelationshipMapper->mapMerchantRelationshipCollectionTransferToApiCollectionTransfer(
            $merchantRelationshipCollectionTransfer,
            $this->apiFacade->createApiCollection([]),
        );

        return $apiCollectionTransfer->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @param int $statusCode
     * @param \Generated\Shared\Transfer\ApiValidationErrorTransfer $apiValidationErrorTransfer
     * @param string|null $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function createApiItemWithValidationError(
        int $statusCode,
        ApiValidationErrorTransfer $apiValidationErrorTransfer,
        ?string $idMerchantRelationship = null
    ): ApiItemTransfer {
        $apiItemTransfer = $this->apiFacade->createApiItem(
            new MerchantRelationshipApiTransfer(),
            $idMerchantRelationship,
        );

        return $apiItemTransfer
            ->setStatusCode($statusCode)
            ->addValidationError($apiValidationErrorTransfer);
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
