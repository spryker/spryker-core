<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\RestRequestValidator\Dependency\Client\RestRequestValidatorToStoreClientInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolver;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\CustomEndpointTransfer;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
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
    protected function setUp(): void
    {
        parent::setUp();

        $mockConfig = $this->createMockConfig();

        $this->restRequestValidatorPlugin = new RestRequestValidator(
            $this->createMockConfigResolver($mockConfig),
            new RestRequestValidatorToValidationAdapter(),
            $mockConfig
        );
    }

    /**
     * @return void
     */
    public function testValidateWillPassOnCorrectRequest(): void
    {
        $mockRestRequest = $this->createMockRestRequestWithData(static::CORRECT_ENDPOINT_DATA);

        $errorTransfer = $this->restRequestValidatorPlugin->validate(
            new Request(),
            $mockRestRequest
        );

        $this->assertNull($errorTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWillPassOnIncorrectRequest(): void
    {
        $mockRestRequest = $this->createMockRestRequestWithData(static::INCORRECT_ENDPOINT_DATA);

        $errorTransfer = $this->restRequestValidatorPlugin->validate(
            new Request(),
            $mockRestRequest
        );

        $this->assertCount(1, $errorTransfer->getRestErrors());
    }

    /**
     * @param string|null $level
     *
     * @return string
     */
    protected function getFixtureDirectory(?string $level = null): string
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
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockConfig(): MockObject
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
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockStoreClient(string $storeName): MockObject
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
        return new RestRequest();
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

    /**
     * @param array $endpointAttributes
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function createMockRestRequestWithData(array $endpointAttributes): RestRequestInterface
    {
        $mockRestRequestObject = $this->createMockRestRequest();
        $mockRestRequest = $mockRestRequestObject->createRestRequest(
            Request::METHOD_POST,
            'custom_endpoint',
            (new CustomEndpointTransfer())->fromArray($endpointAttributes)
        );

        return $mockRestRequest;
    }

    /**
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $mockConfig
     *
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface
     */
    protected function createMockConfigResolver(RestRequestValidatorConfig $mockConfig): RestRequestValidatorConstraintResolverInterface
    {
        return new RestRequestValidatorConstraintResolver(
            new RestRequestValidatorToConstraintCollectionAdapter(),
            $this->createMockConfigReader($mockConfig),
            $mockConfig
        );
    }
}
