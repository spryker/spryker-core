<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\RestRequestValidator\Dependency\Client\RestRequestValidatorToStoreClientInterface;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToConstraintCollectionAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToValidationAdapter;
use Spryker\Glue\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Glue\RestRequestValidator\Processor\Exception\ConstraintNotFoundException;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolver;
use Spryker\Glue\RestRequestValidator\Processor\Validator\Constraint\RestRequestValidatorConstraintResolverInterface;
use Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator;
use Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\EndpointTransfer;
use SprykerTest\Zed\RestRequestValidator\Communication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RestRequestValidator
 * @group Communication
 * @group RestRequestValidatorPluginsTest
 * Add your own group annotations below this line
 */
class RestRequestValidatorPluginsTest extends Unit
{
    protected const VALIDATION_CACHE_FILENAME_PATTERN = '%s/result.validation.yaml';
    protected const VALIDATION_CACHE_WRONG_FILENAME_PATTERN = '%s/wrong.validation.yaml';
    protected const STORE_NAME_DE = 'DE';
    protected const STORE_NAME_AT = 'AT';
    protected const CORRECT_ENDPOINT_DATA = [
        'emailField' => 'tester@test.com',
        'stringField' => 'xxxxxxxx',
        'integerField' => 111111111111111,
        'nested' => [
            'test' => 222222222222222,
            'test_email' => 'tester@test.com',
        ],
    ];
    protected const INCORRECT_ENDPOINT_DATA = [
        'stringField' => 'xxxx',
        'integerField' => 111111,
    ];

    /**
     * @var \SprykerTest\Zed\RestRequestValidator\RestRequestValidatorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateWillPassOnCorrectRequest(): void
    {
        $mockRestRequestValidator = $this->createMockRestRequestValidator();
        $mockRestRequest = $this->createMockRestRequestWithData(static::CORRECT_ENDPOINT_DATA);

        $errorTransfer = $mockRestRequestValidator->validate(
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
        $mockRestRequestValidator = $this->createMockRestRequestValidator();
        $mockRestRequest = $this->createMockRestRequestWithData(static::INCORRECT_ENDPOINT_DATA);

        $errorTransfer = $mockRestRequestValidator->validate(
            new Request(),
            $mockRestRequest
        );

        $this->assertNotNull($errorTransfer);
        $this->assertCount(2, $errorTransfer->getRestErrors());
        $errorTransfer = $errorTransfer->getRestErrors()->offsetGet(0);
        $this->assertInstanceOf(RestErrorMessageTransfer::class, $errorTransfer);
        $this->assertNotEmpty($errorTransfer->getDetail());
        $this->assertNotEmpty($errorTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testValidateWillPassByGetRequest(): void
    {
        $this->expectException(ConstraintNotFoundException::class);

        $mockRestRequestValidator = $this->createMockRestRequestValidatorWithWrongConstraint();
        $mockRestRequest = $this->createMockRestRequestWithData(static::CORRECT_ENDPOINT_DATA);

        $mockRestRequestValidator->validate(
            new Request(),
            $mockRestRequest
        );
    }

    /**
     * @return void
     */
    public function testValidateWillThrowOnNotFoundConstraint(): void
    {
        $mockRestRequestValidator = $this->createMockRestRequestValidator();

        $mockRestRequestObject = $this->createMockRestRequest();
        $mockRestRequest = $mockRestRequestObject->createRestRequest(Request::METHOD_GET);
        $errorTransfer = $mockRestRequestValidator->validate(
            new Request(),
            $mockRestRequest
        );

        $this->assertNull($errorTransfer);
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
                    '\\Symfony\\Component\\Validator\\Constraints\\',
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
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockConfigWithWrongConstraint(): MockObject
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
                    '\\Symfony\\Component\\Validator\\Constraints\\',
                ]
            );
        $mockConfig
            ->method('getValidationCacheFilenamePattern')
            ->willReturn(
                $this->getFixtureDirectory() . static::VALIDATION_CACHE_WRONG_FILENAME_PATTERN
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
        $mockRestRequest = new RestRequest();

        return $mockRestRequest;
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
            'endpoint',
            (new EndpointTransfer())->fromArray($endpointAttributes, true)
        );

        return $mockRestRequest;
    }

    /**
     * @param string $storeName
     * @param \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig $mockConfig
     *
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration\RestRequestValidatorConfigReader
     */
    protected function createMockConfigReader(string $storeName, RestRequestValidatorConfig $mockConfig): RestRequestValidatorConfigReader
    {
        $mockConfigReader = new RestRequestValidatorConfigReader(
            new RestRequestValidatorToFilesystemAdapter(),
            new RestRequestValidatorToYamlAdapter(),
            $this->createMockStoreClient($storeName),
            $mockConfig
        );

        return $mockConfigReader;
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator
     */
    protected function createMockRestRequestValidator(): RestRequestValidator
    {
        $mockConfig = $this->createMockConfig();

        $restRequestValidatorPlugin = new RestRequestValidator(
            $this->createMockConfigResolver($mockConfig),
            new RestRequestValidatorToValidationAdapter(),
            $mockConfig
        );

        return $restRequestValidatorPlugin;
    }

    /**
     * @return \Spryker\Glue\RestRequestValidator\Processor\Validator\RestRequestValidator
     */
    protected function createMockRestRequestValidatorWithWrongConstraint(): RestRequestValidator
    {
        $mockConfig = $this->createMockConfigWithWrongConstraint();

        $restRequestValidatorPlugin = new RestRequestValidator(
            $this->createMockConfigResolver($mockConfig),
            new RestRequestValidatorToValidationAdapter(),
            $mockConfig
        );

        return $restRequestValidatorPlugin;
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
            $this->createMockConfigReader(static::STORE_NAME_DE, $mockConfig),
            $mockConfig
        );
    }
}
