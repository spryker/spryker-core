<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider\SspAssetFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspAssetFormDataProviderTest extends Unit
{
    /**
     * @var int
     */
    protected const BUSINESS_UNIT_ID_1 = 1;

    /**
     * @var int
     */
    protected const BUSINESS_UNIT_ID_2 = 2;

    /**
     * @var int
     */
    protected const COMPANY_ID_1 = 10;

    /**
     * @var int
     */
    protected const COMPANY_ID_2 = 20;

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_NAME_1 = 'Business Unit 1';

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_NAME_2 = 'Business Unit 2';

    /**
     * @var string
     */
    protected const COMPANY_NAME_1 = 'Company 1';

    /**
     * @var string
     */
    protected const COMPANY_NAME_2 = 'Company 2';

    /**
     * @dataProvider expandOptionsWithSubmittedDataDataProvider
     *
     * @param array<string, mixed> $initialOptions
     * @param array<string, mixed> $submittedFormData
     * @param array<string, mixed> $expectedOptions
     * @param array<string, mixed> $expectedAssertions
     *
     * @return void
     */
    public function testExpandOptionsWithSubmittedData(
        array $initialOptions,
        array $submittedFormData,
        array $expectedOptions,
        array $expectedAssertions
    ): void {
        // Arrange
        $formDataProvider = $this->createSspAssetFormDataProvider();

        // Act
        $expandedOptions = $formDataProvider->expandOptionsWithSubmittedData($initialOptions, $submittedFormData);

        // Assert
        foreach ($expectedOptions as $optionKey => $expectedValue) {
            $this->assertArrayHasKey($optionKey, $expandedOptions);
            $this->assertCount(count($expectedValue), $expandedOptions[$optionKey]);
        }

        foreach ($expectedAssertions as $assertion) {
            $optionKey = $assertion['option'];
            $optionSubKey = $assertion['key'] ?? null;
            $expectedValue = $assertion['value'];

            if ($optionSubKey !== null) {
                $this->assertSame($expectedValue, $expandedOptions[$optionKey][$optionSubKey]);
            } else {
                $this->assertSame($expectedValue, current($expandedOptions[$optionKey]));
            }
        }
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function expandOptionsWithSubmittedDataDataProvider(): array
    {
        return [
            'With business units and companies' => [
                'initialOptions' => [
                    SspAssetForm::OPTION_STATUS_OPTIONS => ['active' => 'Active'],
                ],
                'submittedFormData' => [
                    SspAssetForm::FIELD_ASSIGNED_BUSINESS_UNITS => [
                        static::BUSINESS_UNIT_ID_1,
                        static::BUSINESS_UNIT_ID_2,
                    ],
                    SspAssetForm::FIELD_BUSINESS_UNIT_OWNER => static::BUSINESS_UNIT_ID_1,
                    SspAssetForm::FIELD_ASSIGNED_COMPANIES => [
                        static::COMPANY_ID_1,
                        static::COMPANY_ID_2,
                    ],
                ],
                'expectedOptions' => [
                    SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS => [
                        static::BUSINESS_UNIT_NAME_1 => static::BUSINESS_UNIT_ID_1,
                        static::BUSINESS_UNIT_NAME_2 => static::BUSINESS_UNIT_ID_2,
                    ],
                    SspAssetForm::OPTION_BUSINESS_UNIT_OWNER => [
                        static::BUSINESS_UNIT_NAME_1 => static::BUSINESS_UNIT_ID_1,
                    ],
                    SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS => [
                        static::COMPANY_NAME_1 => static::COMPANY_ID_1,
                        static::COMPANY_NAME_2 => static::COMPANY_ID_2,
                    ],
                ],
                'expectedAssertions' => [
                    [
                        'option' => SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS,
                        'key' => static::BUSINESS_UNIT_NAME_1,
                        'value' => static::BUSINESS_UNIT_ID_1,
                    ],
                    [
                        'option' => SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS,
                        'key' => static::BUSINESS_UNIT_NAME_2,
                        'value' => static::BUSINESS_UNIT_ID_2,
                    ],
                    [
                        'option' => SspAssetForm::OPTION_BUSINESS_UNIT_OWNER,
                        'value' => static::BUSINESS_UNIT_ID_1,
                    ],
                    [
                        'option' => SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS,
                        'key' => static::COMPANY_NAME_1,
                        'value' => static::COMPANY_ID_1,
                    ],
                    [
                        'option' => SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS,
                        'key' => static::COMPANY_NAME_2,
                        'value' => static::COMPANY_ID_2,
                    ],
                ],
            ],
            'With no data' => [
                'initialOptions' => [
                    SspAssetForm::OPTION_STATUS_OPTIONS => ['active' => 'Active'],
                ],
                'submittedFormData' => [],
                'expectedOptions' => [
                    SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS => [],
                    SspAssetForm::OPTION_BUSINESS_UNIT_OWNER => [],
                    SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS => [],
                ],
                'expectedAssertions' => [],
            ],
            'With invalid business unit owner' => [
                'initialOptions' => [
                    SspAssetForm::OPTION_STATUS_OPTIONS => ['active' => 'Active'],
                ],
                'submittedFormData' => [
                    SspAssetForm::FIELD_ASSIGNED_BUSINESS_UNITS => [static::BUSINESS_UNIT_ID_2],
                    SspAssetForm::FIELD_BUSINESS_UNIT_OWNER => static::BUSINESS_UNIT_ID_1, // Not in assigned business units
                ],
                'expectedOptions' => [
                    SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS => [
                        static::BUSINESS_UNIT_NAME_2 => static::BUSINESS_UNIT_ID_2,
                    ],
                    SspAssetForm::OPTION_BUSINESS_UNIT_OWNER => [],
                    SspAssetForm::OPTION_COMPANY_ASSIGMENT_OPTIONS => [],
                ],
                'expectedAssertions' => [
                    [
                        'option' => SspAssetForm::OPTION_BUSINESS_UNIT_ASSIGMENT_OPTIONS,
                        'key' => static::BUSINESS_UNIT_NAME_2,
                        'value' => static::BUSINESS_UNIT_ID_2,
                    ],
                ],
            ],
        ];
    }

    protected function createSspAssetFormDataProvider(): SspAssetFormDataProvider
    {
        return new SspAssetFormDataProvider(
            $this->createSelfServicePortalFacadeMock(),
            $this->createSelfServicePortalConfigMock(),
            $this->createCompanyBusinessUnitFacadeMock(),
            $this->createCompanyFacadeMock(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSelfServicePortalFacadeMock(): SelfServicePortalFacadeInterface
    {
        return $this->createMock(SelfServicePortalFacadeInterface::class);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSelfServicePortalConfigMock(): SelfServicePortalConfig
    {
        return $this->createMock(SelfServicePortalConfig::class);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCompanyBusinessUnitFacadeMock(): CompanyBusinessUnitFacadeInterface
    {
        $companyBusinessUnitFacadeMock = $this->createMock(CompanyBusinessUnitFacadeInterface::class);

        $companyBusinessUnitFacadeMock
            ->method('getCompanyBusinessUnitCollection')
            ->willReturnCallback(function (CompanyBusinessUnitCriteriaFilterTransfer $criteriaFilter) {
                $businessUnitCollection = new CompanyBusinessUnitCollectionTransfer();

                foreach ($criteriaFilter->getCompanyBusinessUnitIds() as $businessUnitId) {
                    if ($businessUnitId === static::BUSINESS_UNIT_ID_1) {
                        $businessUnitCollection->addCompanyBusinessUnit(
                            (new CompanyBusinessUnitTransfer())
                                ->setIdCompanyBusinessUnit(static::BUSINESS_UNIT_ID_1)
                                ->setName(static::BUSINESS_UNIT_NAME_1),
                        );
                    }

                    if ($businessUnitId === static::BUSINESS_UNIT_ID_2) {
                        $businessUnitCollection->addCompanyBusinessUnit(
                            (new CompanyBusinessUnitTransfer())
                                ->setIdCompanyBusinessUnit(static::BUSINESS_UNIT_ID_2)
                                ->setName(static::BUSINESS_UNIT_NAME_2),
                        );
                    }
                }

                return $businessUnitCollection;
            });

        return $companyBusinessUnitFacadeMock;
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCompanyFacadeMock(): CompanyFacadeInterface
    {
        $companyFacadeMock = $this->createMock(CompanyFacadeInterface::class);

        $companyFacadeMock
            ->method('getCompanyCollection')
            ->willReturnCallback(function (CompanyCriteriaFilterTransfer $criteriaFilter) {
                $companyCollection = new CompanyCollectionTransfer();

                foreach ($criteriaFilter->getCompanyIds() as $companyId) {
                    if ($companyId === static::COMPANY_ID_1) {
                        $companyCollection->addCompany(
                            (new CompanyTransfer())
                                ->setIdCompany(static::COMPANY_ID_1)
                                ->setName(static::COMPANY_NAME_1),
                        );
                    }

                    if ($companyId === static::COMPANY_ID_2) {
                        $companyCollection->addCompany(
                            (new CompanyTransfer())
                                ->setIdCompany(static::COMPANY_ID_2)
                                ->setName(static::COMPANY_NAME_2),
                        );
                    }
                }

                return $companyCollection;
            });

        return $companyFacadeMock;
    }
}
