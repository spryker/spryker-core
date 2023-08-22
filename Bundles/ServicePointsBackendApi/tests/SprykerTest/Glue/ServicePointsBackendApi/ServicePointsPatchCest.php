<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi;

use Codeception\Example;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServicePointsBackendResourcePlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group ServicePointsPatchCest
 * Add your own group annotations below this line
 */
class ServicePointsPatchCest
{
    /**
     * @var string
     */
    protected const TEST_UUID = 'TEST_UUID';

    /**
     * @var string
     */
    protected const TEST_KEY = 'TEST_KEY';

    /**
     * @var string
     */
    protected const TEST_KEY_2 = 'TEST_KEY_2';

    /**
     * @var string
     */
    protected const TEST_STORE = 'TEST_STORE';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS
     *
     * @var string
     */
    protected const RESOURCE_SERVICE_POINTS = 'service-points';

    /**
     * @var string
     */
    protected const KEY_SERVICE_POINT_PATCH_DATA = 'servicePointPatchData';

    /**
     * @var string
     */
    protected const KEY_EXPECTED_SERVICE_POINT_DATA = 'expectedServicePointData';

    /**
     * @var string
     */
    protected const KEY_EXPECTED_CODE = 'expectedCode';

    /**
     * @var string
     */
    protected const KEY_CODE = 'code';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESPONSE_CODE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const RESPONSE_CODE_ENTITY_NOT_FOUND = '5403';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESPONSE_CODE_SERVICE_POINT_KEY_EXISTS
     *
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_KEY_EXISTS = '5404';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESPONSE_CODE_SERVICE_POINT_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_KEY_WRONG_LENGTH = '5405';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESPONSE_CODE_SERVICE_POINT_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_NAME_WRONG_LENGTH = '5407';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESPONSE_CODE_STORE_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const RESPONSE_CODE_STORE_DOES_NOT_EXIST = '5408';

    /**
     * @dataProvider getPatchServicePointDataProvider
     *
     * @param \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester $I
     * @param \Codeception\Example $example
     *
     * @return void
     */
    public function requestPatch(ServicePointsBackendApiTester $I, Example $example): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new ServicePointsBackendResourcePlugin());

        $servicePointTransfer = $I->createServicePointTransfer();

        // Act
        $I->sendPatch($I->buildServicePointUrl($servicePointTransfer->getUuid()), [
            'data' => [
                GlueRequestTransfer::ATTRIBUTES => $example[static::KEY_SERVICE_POINT_PATCH_DATA],
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs($example[static::KEY_EXPECTED_CODE]);
        $I->seeResponseIsJson();
        $I->seeResponseJsonPathContains($example[static::KEY_EXPECTED_SERVICE_POINT_DATA]);
    }

    /**
     * @param \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester $I
     *
     * @return void
     */
    public function requestPatchShouldReturnNotFoundWhenEntityIsNotFound(ServicePointsBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new ServicePointsBackendResourcePlugin());

        // Act
        $I->sendPatch($I->buildServicePointUrl(static::TEST_UUID), [
            'data' => [
                GlueRequestTransfer::ATTRIBUTES => [
                    ServicePointsBackendApiAttributesTransfer::KEY => static::TEST_KEY,
                ],
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseJsonPathContains([
            'errors' => [[static::KEY_CODE => static::RESPONSE_CODE_ENTITY_NOT_FOUND]],
        ]);
    }

    /**
     * @param \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester $I
     *
     * @return void
     */
    public function requestPatchShouldReturnBadRequestWhenKeyIsNotUnique(ServicePointsBackendApiTester $I): void
    {
        // Arrange
        $I->addJsonApiResourcePlugin(new ServicePointsBackendResourcePlugin());

        $servicePointTransfer = $I->createServicePointTransfer([
            ServicePointTransfer::KEY => static::TEST_KEY,
        ]);
        $I->createServicePointTransfer([
            ServicePointTransfer::KEY => static::TEST_KEY_2,
        ]);

        // Act
        $I->sendPatch($I->buildServicePointUrl($servicePointTransfer->getUuid()), [
            'data' => [
                GlueRequestTransfer::ATTRIBUTES => [
                    ServicePointsBackendApiAttributesTransfer::KEY => static::TEST_KEY_2,
                ],
            ],
        ]);

        // Assert
        $I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseJsonPathContains([
            'errors' => [[static::KEY_CODE => static::RESPONSE_CODE_SERVICE_POINT_KEY_EXISTS]],
        ]);
    }

    /**
     * @return array<string, array<array<string, mixed>|int>>
     */
    protected function getPatchServicePointDataProvider(): array
    {
        return [
            'Should return success when boolean value for is active is provided' => [
                static::KEY_SERVICE_POINT_PATCH_DATA => [ServicePointsBackendApiAttributesTransfer::IS_ACTIVE => false],
                static::KEY_EXPECTED_CODE => Response::HTTP_OK,
                static::KEY_EXPECTED_SERVICE_POINT_DATA => [
                    'data' => [
                        'type' => static::RESOURCE_SERVICE_POINTS,
                        'attributes' => [ServicePointsBackendApiAttributesTransfer::IS_ACTIVE => false],
                    ],
                ],
            ],
            'Should return success when string value for is active is provided' => [
                static::KEY_SERVICE_POINT_PATCH_DATA => [ServicePointsBackendApiAttributesTransfer::IS_ACTIVE => 'false'],
                static::KEY_EXPECTED_CODE => Response::HTTP_OK,
                static::KEY_EXPECTED_SERVICE_POINT_DATA => [
                    'data' => [
                        'type' => static::RESOURCE_SERVICE_POINTS,
                        'attributes' => [ServicePointsBackendApiAttributesTransfer::IS_ACTIVE => false],
                    ],
                ],
            ],
            'Should return bad request when empty value for key is provided' => [
                static::KEY_SERVICE_POINT_PATCH_DATA => [ServicePointsBackendApiAttributesTransfer::KEY => ''],
                static::KEY_EXPECTED_CODE => Response::HTTP_BAD_REQUEST,
                static::KEY_EXPECTED_SERVICE_POINT_DATA => [
                    'errors' => [[static::KEY_CODE => static::RESPONSE_CODE_SERVICE_POINT_KEY_WRONG_LENGTH]],
                ],
            ],
            'Should return bad request when empty value for name is provided' => [
                static::KEY_SERVICE_POINT_PATCH_DATA => [ServicePointsBackendApiAttributesTransfer::NAME => ''],
                static::KEY_EXPECTED_CODE => Response::HTTP_BAD_REQUEST,
                static::KEY_EXPECTED_SERVICE_POINT_DATA => [
                    'errors' => [[static::KEY_CODE => static::RESPONSE_CODE_SERVICE_POINT_NAME_WRONG_LENGTH]],
                ],
            ],
            'Should return bad request when wrong store is provided' => [
                static::KEY_SERVICE_POINT_PATCH_DATA => [ServicePointsBackendApiAttributesTransfer::STORES => [static::TEST_STORE]],
                static::KEY_EXPECTED_CODE => Response::HTTP_BAD_REQUEST,
                static::KEY_EXPECTED_SERVICE_POINT_DATA => [
                    'errors' => [[static::KEY_CODE => static::RESPONSE_CODE_STORE_DOES_NOT_EXIST]],
                ],
            ],
        ];
    }
}
