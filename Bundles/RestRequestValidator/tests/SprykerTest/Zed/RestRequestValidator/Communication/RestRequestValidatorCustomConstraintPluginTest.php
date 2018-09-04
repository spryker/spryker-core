<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\RestRequestValidator\Dependency\Client\RestRequestValidatorToStoreClientInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolver;
use Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\CustomEndpointTransfer;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestRequestValidator
 * @group Communication
 * @group RestRequestValidatorCustomConstraintPluginTest
 * Add your own group annotations below this line
 */
class RestRequestValidatorCustomConstraintPluginTest extends Unit
{
    protected const VALIDATION_CACHE_FILENAME_PATTERN = '%s/custom.validation.yaml';
    protected const STORE_NAME_DE = 'DE';
    protected const STORE_NAME_AT = 'AT';
    protected const CORRECT_ENDPOINT_DATA = [
        'currencyCode' => 'FJD',
    ];
    protected const INCORRECT_ENDPOINT_DATA = [
        'currencyCode' => 'xxx',
    ];

    /**
     * @var \SprykerTest\Zed\RestRequestValidator\RestRequestValidatorBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator
     */
    protected $restRequestValidatorPlugin;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $mockConfig = $this->createMockConfig();
        $mockConfigReader = $this->createMockConfigReader($mockConfig);
        $mockConstraintResolver = new RestRequestValidatorConstraintResolver($mockConfig);

        $this->restRequestValidatorPlugin = new RestRequestValidator(
            $mockConfigReader,
            $mockConstraintResolver,
            $mockConfig
        );
    }

    /**
     * @return void
     */
    public function testValidateWillPassOnCorrectRequest(): void
    {
        // prepare
        $mockRestRequestObject = $this->createMockRestRequest();
        $mockRestRequest = $mockRestRequestObject->createRestRequest(
            Request::METHOD_POST,
            'custom_endpoint',
            (new CustomEndpointTransfer())->fromArray(
                static::CORRECT_ENDPOINT_DATA
            )
        );

        // act
        $errorTransfer = $this->restRequestValidatorPlugin->validate(
            new Request(),
            $mockRestRequest
        );

        // assert
        $this->assertNull($errorTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWillPassOnIncorrectRequest(): void
    {
        // prepare
        $mockRestRequestObject = $this->createMockRestRequest();
        $mockRestRequest = $mockRestRequestObject->createRestRequest(
            Request::METHOD_POST,
            'custom_endpoint',
            (new CustomEndpointTransfer())->fromArray(
                static::INCORRECT_ENDPOINT_DATA
            )
        );

        // act
        $errorTransfer = $this->restRequestValidatorPlugin->validate(
            new Request(),
            $mockRestRequest
        );

        // assert
        $this->assertNotNull($errorTransfer);
        $this->assertCount(1, $errorTransfer->getRestErrors());
        $errorTransfer = $errorTransfer->getRestErrors()->offsetGet(0);
        $this->assertInstanceOf(RestErrorMessageTransfer::class, $errorTransfer);
        $this->assertNotEmpty($errorTransfer->getDetail());
        $this->assertNotEmpty($errorTransfer->getCode());
    }

    /**
     * @param string|null $level
     *
     * @return string
     */
    protected function getFixtureDirectory($level = null)
    {
        $pathParts = [
            __DIR__,
            'Fixtures',
            'Validation',
        ];

        if ($level) {
            $pathParts[] = $level;
        }

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig
     */
    protected function createMockConfig(): RestRequestValidatorConfig
    {
        $mockConfig = $this->createPartialMock(
            RestRequestValidatorConfig::class,
            [
                'getAvailableConstraintNamespaces',
                'getValidationCacheFilenamePattern',
            ]
        );

        $mockConfig
            ->method('getAvailableConstraintNamespaces')
            ->willReturn(
                [
                    '\\SprykerTest\\Zed\\RestRequestValidator\\Communication\\Stub\\Constraint\\',
                ]
            );
        $mockConfig
            ->method('getValidationCacheFilenamePattern')
            ->willReturn(
                $this->getFixtureDirectory() . static::VALIDATION_CACHE_FILENAME_PATTERN
            );

        return $mockConfig;
    }

    /**
     * @param string $storeName
     *
     * @return \Spryker\Glue\RestRequestValidator\Dependency\Client\RestRequestValidatorToStoreClientInterface
     */
    protected function createMockStoreClient(string $storeName): RestRequestValidatorToStoreClientInterface
    {
        $mockStoreClient = $this->createPartialMock(
            RestRequestValidatorToStoreClientInterface::class,
            [
                'getCurrentStore',
            ]
        );

        $mockStoreClient
            ->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($storeName));

        return $mockStoreClient;
    }

    /**
     * @return \SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequest
     */
    protected function createMockRestRequest(): RestRequest
    {
        $mockRestRequest = new RestRequest();
        return $mockRestRequest;
    }

    /**
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $mockConfig
     *
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader
     */
    protected function createMockConfigReader(RestRequestValidatorConfig $mockConfig): RestRequestValidatorConfigReader
    {
        $mockConfigReader = new RestRequestValidatorConfigReader(
            new RestRequestValidatorToFilesystemAdapter(),
            new RestRequestValidatorToYamlAdapter(),
            $this->createMockStoreClient(static::STORE_NAME_DE),
            $mockConfig
        );
        return $mockConfigReader;
    }
}
