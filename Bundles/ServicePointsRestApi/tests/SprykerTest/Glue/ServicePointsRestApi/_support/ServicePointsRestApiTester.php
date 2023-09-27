<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsRestApi;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\ServicePointsRestApi\PHPMD)
 */
class ServicePointsRestApiTester extends Actor
{
    use _generated\ServicePointsRestApiTesterActions;

    /**
     * @uses \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE
     *
     * @var string
     */
    protected const CLIENT_SERVICE_POINT_STORAGE = 'CLIENT_SERVICE_POINT_STORAGE';

    /**
     * @uses \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA
     *
     * @var string
     */
    protected const RESOURCE_CHECKOUT_DATA = 'checkout-data';

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCheckoutDataRestResource(array $servicePointTransfers): RestResourceInterface
    {
        $restResource = new RestResource(
            static::RESOURCE_CHECKOUT_DATA,
            '',
            new RestCheckoutDataResponseAttributesTransfer(),
        );

        $restResource->setPayload(
            (new RestCheckoutDataTransfer())
                ->setServicePoints(new ArrayObject($servicePointTransfers)),
        );

        return $restResource;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\RestServicePointTransfer> $restServicePointTransfers
     *
     * @return void
     */
    public function mockGetServicePointStorageCollection(ArrayObject $restServicePointTransfers): void
    {
        $servicePointStorageCollectionTransfer = new ServicePointStorageCollectionTransfer();
        foreach ($restServicePointTransfers as $restServicePointTransfer) {
            $servicePointStorageCollectionTransfer->addServicePointStorage(
                (new ServicePointStorageTransfer())
                    ->setUuid($restServicePointTransfer->getIdServicePoint()),
            );
        }

        $this->setDependency(
            static::CLIENT_SERVICE_POINT_STORAGE,
            Stub::makeEmpty(ServicePointsRestApiToServicePointStorageClientInterface::class, [
                'getServicePointStorageCollection' => $servicePointStorageCollectionTransfer,
            ]),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param string $responseCode
     * @param string $responseDetail
     *
     * @return void
     */
    public function assertRestErrorMessageTransfer(
        RestErrorMessageTransfer $restErrorMessageTransfer,
        string $responseCode,
        string $responseDetail
    ): void {
        $this->assertSame($responseCode, $restErrorMessageTransfer->getCode());
        $this->assertSame($responseDetail, $restErrorMessageTransfer->getDetail());
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $restErrorMessageTransfer->getStatus());
    }

    /**
     * @param array<mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointTransfer(array $seed = []): ServicePointTransfer
    {
        return (new ServicePointBuilder($seed))->build();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function createRestRequestMock(): RestRequestInterface
    {
        return Stub::makeEmpty(RestRequestInterface::class);
    }
}
