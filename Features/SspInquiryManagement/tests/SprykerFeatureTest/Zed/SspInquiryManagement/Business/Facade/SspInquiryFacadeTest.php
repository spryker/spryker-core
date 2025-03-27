<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspInquiryManagement\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineItemStateTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;
use SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConfig as SprykerFeatureSspInquiryConfig;
use SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementBusinessFactory;
use SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacade;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\StateMachine\SspInquiryStateMachineHandlerPlugin;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspInquiry
 * @group Business
 * @group SspInquiryFacadeTest
 */
class SspInquiryFacadeTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspInquiryManagement\SspInquiryBusinessTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacade
     */
    protected $sspInquiryManagementFacade;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected CompanyUserTransfer $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var string
     */
    protected const PLUGINS_STATE_MACHINE_HANDLERS = 'PLUGINS_STATE_MACHINE_HANDLERS';

    /**
     * @var string
     */
    protected const LOCALE_CURRENT = 'LOCALE_CURRENT';

    /**
     * @var string
     */
    protected const SERVICE_FILE_SYSTEM = 'SERVICE_FILE_SYSTEM';

    /**
     * @var string
     */
    protected const FACADE_STATE_MACHINE = 'FACADE_STATE_MACHINE';

    /**
     * @return void
     */
    protected function _before(): void
    {
        $fileSystemServiceMock = $this->getMockBuilder(FileManagerToFileSystemServiceInterface::class)->getMock();

        $configMock = $this->getMockBuilder(SspInquiryManagementConfig::class)->onlyMethods(
            ['getSspInquiryInitialStateMap', 'getSspInquiryStateMachineProcessSspInquiryTypeMap'],
        )->getMock();
        $configMock->method('getSspInquiryInitialStateMap')->willReturn([
            'test_general_ssp_inquiry' => 'new',
            'test_order_ssp_inquiry' => 'created',
        ]);
        $configMock->method('getSspInquiryStateMachineProcessSspInquiryTypeMap')->willReturn([
            'general' => 'test_general_ssp_inquiry',
            'order' => 'test_order_ssp_inquiry',
            'test_general_type' => 'test_general_ssp_inquiry',
            'test_order_type' => 'test_order_ssp_inquiry',
            'test_product_type' => 'test_product_type',
            'ssp_asset' => 'test_ssp_asset_ssp_inquiry',
        ]);

        $this->tester->setDependency(static::SERVICE_FILE_SYSTEM, $fileSystemServiceMock);
        $this->tester->setDependency(static::LOCALE_CURRENT, 'en_US');
        $this->tester->setDependency(static::PLUGINS_STATE_MACHINE_HANDLERS, [
            (new SspInquiryStateMachineHandlerPlugin()),
        ]);

        $stateMachineFacadeMock = $this->getMockBuilder(StateMachineFacade::class)
            ->onlyMethods(['triggerForNewStateMachineItem', 'getStateMachineProcessId', 'getStateHistoryByStateItemIdentifier'])
            ->getMock();

        $stateMachineFacadeMock->method('triggerForNewStateMachineItem')->willReturn(1);
        $stateMachineFacadeMock->method('getStateMachineProcessId')->willReturn(1);
        $stateMachineFacadeMock->method('getStateHistoryByStateItemIdentifier')->willReturn([]);

        $this->tester->setDependency(static::FACADE_STATE_MACHINE, $stateMachineFacadeMock);

        $configMock->setSharedConfig(new SprykerFeatureSspInquiryConfig());
        $this->sspInquiryManagementFacade = (new SspInquiryManagementFacade())->setFactory(
            (new SspInquiryManagementBusinessFactory())->setConfig($configMock),
        );
        $this->customerTransfer = $this->tester->haveCustomer();
        $this->companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);

        $this->storeTransfer = $this->tester->haveStore();
    }

    /**
     * @dataProvider sspInquirySuccessfulCollectionDataProvider
     *
     * @param array<mixed> $sspInquiryData
     * @param int $expectedSspInquiryCount
     * @param string $expectedSubject
     * @param int $expectedFileCount
     * @param array<int, int> $expectedValidationErrors
     *
     * @return void
     */
    public function testCreateSspInquiryCollectionIsSuccessful(
        array $sspInquiryData,
        int $expectedSspInquiryCount,
        string $expectedSubject,
        int $expectedFileCount,
        array $expectedValidationErrors
    ): void {
        // Arrange
         $sspInquiryTransfer = (new SspInquiryTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setSubject($sspInquiryData['subject'])
            ->setType($sspInquiryData['type'])
            ->setDescription($sspInquiryData['description'])
            ->setStore($this->storeTransfer);

        foreach ($sspInquiryData['files'] as $fileData) {
            $fileTransfer = new FileTransfer();
            $fileTransfer->setEncodedContent(base64_encode(gzencode($fileData['content'])));
            $fileTransfer->setFileUpload(
                (new FileUploadTransfer())
                    ->setSize($fileData['size'])
                    ->setMimeTypeName($fileData['mimeType'])
                    ->setRealPath($fileData['realPath'])
                    ->setClientOriginalExtension($fileData['extension']),
            );
             $sspInquiryTransfer->addFile($fileTransfer);
        }

        if (isset($sspInquiryData['orderReference'])) {
             $sspInquiryTransfer->setOrder(
                 (new OrderTransfer())
                    ->setOrderReference($sspInquiryData['orderReference'])
                    ->setCustomerReference($this->customerTransfer->getCustomerReference()),
             );
        }

        if (isset($sspInquiryData['assetReference'])) {
            $this->tester->haveAsset([SspAssetTransfer::REFERENCE => $sspInquiryData['assetReference']]);
            $sspInquiryTransfer->setSspAsset((new SspAssetTransfer())->setReference($sspInquiryData['assetReference']));
        }

        // Act
         $sspInquiryCollectionResponseTransfer = $this->sspInquiryManagementFacade->createSspInquiryCollection(
             (new SspInquiryCollectionRequestTransfer())->addSspInquiry($sspInquiryTransfer),
         );

        // Assert
        $this->assertNotNull($sspInquiryCollectionResponseTransfer);
        $this->assertCount($expectedSspInquiryCount, $sspInquiryCollectionResponseTransfer->getSspInquiries());
        if ($expectedSspInquiryCount > 0) {
            $createdSspInquiryTransfer = $sspInquiryCollectionResponseTransfer->getSspInquiries()[0];
            $this->assertNotNull($createdSspInquiryTransfer->getIdSspInquiry());
            $this->assertSame($expectedSubject, $createdSspInquiryTransfer->getSubject());
            $this->assertCount($expectedFileCount, $createdSspInquiryTransfer->getFiles());
            foreach ($createdSspInquiryTransfer->getFiles() as $fileTransfer) {
                $this->assertNotNull($fileTransfer->getIdFile());
            }

            if (isset($sspInquiryData['orderReference'])) {
                $this->assertNotNull($createdSspInquiryTransfer->getOrder());
            }

            if (isset($sspInquiryData['assetReference'])) {
                $this->assertSame($sspInquiryData['assetReference'], $createdSspInquiryTransfer->getSspAsset()->getReference());
            }

            return;
        }

        $this->assertSame(
            $expectedValidationErrors,
            array_map(
                fn (ErrorTransfer $errorTransfer) => $errorTransfer->getMessage(),
                $sspInquiryCollectionResponseTransfer->getErrors()->getArrayCopy(),
            ),
        );

        $this->assertCount(3, $sspInquiryCollectionResponseTransfer->getErrors());
    }

    /**
     * @dataProvider negativeSspInquiryCollectionDataProvider
     *
     * @param array<mixed> $sspInquiryData
     * @param string $expectedExceptionMessage
     *
     * @return void
     */
    public function testCreateSspInquiryCollectionIsNotSuccessful(array $sspInquiryData, string $expectedExceptionMessage): void
    {
        // Arrange
         $sspInquiryTransfer = (new SspInquiryTransfer())
            ->setCompanyUser($this->companyUserTransfer)
            ->setSubject($sspInquiryData['subject'])
            ->setType($sspInquiryData['type'])
            ->setDescription($sspInquiryData['description'])
            ->setStore($this->storeTransfer);

        foreach ($sspInquiryData['files'] as $fileData) {
            $fileTransfer = new FileTransfer();
            $fileTransfer->setEncodedContent(base64_encode(gzencode($fileData['content'])));
            $fileTransfer->setFileUpload(
                (new FileUploadTransfer())
                    ->setSize($fileData['size'])
                    ->setMimeTypeName($fileData['mimeType'])
                    ->setRealPath($fileData['realPath'])
                    ->setClientOriginalExtension($fileData['extension']),
            );
             $sspInquiryTransfer->addFile($fileTransfer);
        }

        if (isset($sspInquiryData['orderReference'])) {
             $sspInquiryTransfer->setOrder(
                 (new OrderTransfer())
                    ->setOrderReference($sspInquiryData['orderReference'])
                    ->setCustomerReference($this->customerTransfer->getCustomerReference()),
             );
        }

        if (isset($sspInquiryData['assetReference'])) {
            $sspInquiryTransfer->setSspAsset((new SspAssetTransfer())->setReference($sspInquiryData['assetReference']));
        }

        // Assert
        $this->expectExceptionMessage($expectedExceptionMessage);

        // Act
        $this->sspInquiryManagementFacade->createSspInquiryCollection(
            (new SspInquiryCollectionRequestTransfer())->addSspInquiry($sspInquiryTransfer),
        );
    }

    /**
     * @dataProvider getSspInquiryCollectionDataProvider
     *
     * @param array<string, string> $filters
     * @param array<string, string> $sorting
     * @param array<array<string, string>> $expectedSspInquiries
     *
     * @return void
     */
    public function testGetSspInquiryCollection(array $filters, array $sorting, array $expectedSspInquiries): void
    {
        // Arrange
         $sspInquiryStateMachineProcess = $this->tester->haveStateMachineProcess([
            StateMachineProcessTransfer::STATE_MACHINE_NAME => 'test_ssp_inquiry',
         ]);

        $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $sspInquiryStateMachineProcess->getIdStateMachineProcess(),
            StateMachineItemStateTransfer::NAME => 'test_initial_state',
        ]);

        $this->tester->haveStateMachineItemState([
            StateMachineItemStateTransfer::FK_STATE_MACHINE_PROCESS => $sspInquiryStateMachineProcess->getIdStateMachineProcess(),
            StateMachineItemStateTransfer::NAME => 'test_final_state',
        ]);

        SpyStateMachineItemStateQuery::create()->find();
        $this->tester->haveSspInquiry([
            SspInquiryTransfer::TYPE => 'test_general_type',
            SspInquiryTransfer::STATUS => 'test_initial_state',
            SspInquiryTransfer::STORE => $this->storeTransfer,
            SspInquiryTransfer::CREATED_DATE => '2021-01-01 00:00:00',
        ]);
        $this->tester->haveSspInquiry([
            SspInquiryTransfer::TYPE => 'test_product_type',
            SspInquiryTransfer::STATUS => 'test_final_state',
            SspInquiryTransfer::STORE => $this->storeTransfer,
            SspInquiryTransfer::CREATED_DATE => '2021-01-02 00:00:00',
        ]);
        $this->tester->haveSspInquiry([
            SspInquiryTransfer::TYPE => 'test_order_type',
            SspInquiryTransfer::STATUS => 'test_initial_state',
            SspInquiryTransfer::STORE => $this->storeTransfer,
            SspInquiryTransfer::CREATED_DATE => '2021-01-03 00:00:00',
        ]);

         $sspInquiryCriteriaTransfer = (new SspInquiryCriteriaTransfer())->setSspInquiryConditions(
             new SspInquiryConditionsTransfer(),
         );
        foreach ($filters as $filter => $value) {
            call_user_func([$sspInquiryCriteriaTransfer->getSspInquiryConditions(), 'set' . ucfirst($filter)], $value);
        }

        foreach ($sorting as $field => $direction) {
             $sspInquiryCriteriaTransfer->addSort(
                 (new SortTransfer())
                    ->setField($field)
                    ->setIsAscending($direction === 'ASC'),
             );
        }

        // Act
         $sspInquiryCollectionTransfer = $this->sspInquiryManagementFacade->getSspInquiryCollection(
             $sspInquiryCriteriaTransfer,
         );

        // Assert
        $this->assertCount(count($expectedSspInquiries), $sspInquiryCollectionTransfer->getSspInquiries());

        foreach ($expectedSspInquiries as $key => $expectedSspInquiry) {
            $this->assertSame($expectedSspInquiry['type'], $sspInquiryCollectionTransfer->getSspInquiries()->offsetGet($key)->getType());
            $this->assertSame($expectedSspInquiry['status'], $sspInquiryCollectionTransfer->getSspInquiries()->offsetGet($key)->getStatus());
        }
    }

    /**
     * @return array<mixed>
     */
    public function sspInquirySuccessfulCollectionDataProvider(): array
    {
        return [
            'success' => [
                'sspInquiryData' => [
                    'subject' => 'Test Ssp Inquiry',
                    'type' => 'general',
                    'description' => 'Test Description',
                    'files' => [],
                ],
                'expectedSspInquiryCount' => 1,
                'expectedSubject' => 'Test Ssp Inquiry',
                'expectedFileCount' => 0,
                'expectedValidationErrors' => [],
            ],
            'ssp inquiry not valid' => [
                'sspInquiryData' => [
                    'subject' => null,
                    'type' => null,
                    'description' => null,
                    'files' => [],
                ],
                'expectedSspInquiryCount' => 0,
                'expectedSubject' => '',
                'expectedFileCount' => 0,
                'expectedValidationErrors' => [
                    'ssp_inquiry.validation.type.not_set',
                    'ssp_inquiry.validation.subject.not_set',
                    'ssp_inquiry.validation.description.not_set',
                ],
            ],
            'withFiles' => [
                'sspInquiryData' => [
                    'subject' => 'Test Ssp Inquiry with Files',
                    'type' => 'general',
                    'description' => 'Test Description',
                    'files' => [
                        [
                            'content' => 'test content',
                            'size' => 100,
                            'mimeType' => 'text/plain',
                            'realPath' => 'testfile.txt',
                            'extension' => 'txt',
                        ],
                    ],
                ],
                'expectedSspInquiryCount' => 1,
                'expectedSubject' => 'Test Ssp Inquiry with Files',
                'expectedFileCount' => 1,
                'expectedValidationErrors' => [],
            ],
            'order ssp inquiry' => [
                'sspInquiryData' => [
                    'subject' => 'Test Order Ssp Inquiry',
                    'type' => 'order',
                    'description' => 'Test Description',
                    'orderReference' => 'test_order_reference',
                    'files' => [],
                ],
                'expectedSspInquiryCount' => 1,
                'expectedSubject' => 'Test Order Ssp Inquiry',
                'expectedFileCount' => 0,
                'expectedValidationErrors' => [],
            ],
            'ssp asset ssp inquiry' => [
                'sspInquiryData' => [
                    'subject' => 'Test SSP Asset Ssp Inquiry',
                    'type' => 'ssp_asset',
                    'description' => 'Test SSP Asset Description',
                    'files' => [],
                    'assetReference' => 'test_asset_reference',
                ],
                'expectedSspInquiryCount' => 1,
                'expectedSubject' => 'Test SSP Asset Ssp Inquiry',
                'expectedFileCount' => 0,
                'expectedValidationErrors' => [],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public function negativeSspInquiryCollectionDataProvider(): array
    {
        return [
            'order ssp inquiry without order reference' => [
                'sspInquiryData' => [
                    'subject' => 'Test Order Ssp Inquiry',
                    'type' => 'order',
                    'description' => 'Test Description',
                    'files' => [],
                ],
                'expectedExceptionMessage' => 'Missing required property "order" for transfer Generated\Shared\Transfer\SspInquiryTransfer',
            ],
            'ssp asset ssp inquiry without asset reference' => [
                'sspInquiryData' => [
                    'subject' => 'Test SSP Asset Ssp Inquiry',
                    'type' => 'ssp_asset',
                    'description' => 'Test Description',
                    'files' => [],
                ],
                'expectedExceptionMessage' => 'Missing required property "sspAsset" for transfer Generated\Shared\Transfer\SspInquiryTransfer.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getSspInquiryCollectionDataProvider(): array
    {
        return [
            'get ssp inquiries with test_initial_state' => [
                'filters' => [
                    'status' => 'test_initial_state',
                    ],
                'sorting' => [],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_general_type',
                        'status' => 'test_initial_state',
                    ],
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
            'get ssp inquiries with test_final_state' => [
                'filters' => [
                    'status' => 'test_final_state',
                ],
                'sorting' => [],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_product_type',
                        'status' => 'test_final_state',
                    ],
                ],
            ],
            'get ssp inquiries with test_order_type type' => [
                'filters' => [
                    'type' => 'test_order_type',
                ],
                'sorting' => [],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
            'get ssp inquiries with 2021-01-02 date' => [
                'filters' => [
                    'createdDateTo' => '2021-01-02',
                ],
                'sorting' => [],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_general_type',
                        'status' => 'test_initial_state',
                    ],
                    [
                        'type' => 'test_product_type',
                        'status' => 'test_final_state',
                    ],
                ],
            ],
            'get ssp inquiries with from 2021-01-02 to 2021-01-03 date' => [
                'filters' => [
                    'createdDateFrom' => '2021-01-02',
                    'createdDateTo' => '2021-01-03',
                ],
                'sorting' => [
                    'type' => 'DESC',
                ],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_product_type',
                        'status' => 'test_final_state',
                    ],
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
            'get ssp inquiries with test_initial_state and sorting by type ASC' => [
                'filters' => [
                    'status' => 'test_initial_state',
                ],
                'sorting' => [
                    'type' => 'ASC',
                ],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_general_type',
                        'status' => 'test_initial_state',
                    ],
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
            'get ssp inquiries with test_initial_state and sorting by type DESC' => [
                'filters' => [
                    'status' => 'test_initial_state',
                ],
                'sorting' => [
                    'type' => 'DESC',
                ],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                    [
                        'type' => 'test_general_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
            'get ssp inquiries with test_initial_state and sorting by date DESC' => [
                'filters' => [
                    'status' => 'test_initial_state',
                ],
                'sorting' => [
                    'created_at' => 'DESC',
                ],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                    [
                        'type' => 'test_general_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
            'get ssp inquiries with test_initial_state and sorting by date ASC' => [
                'filters' => [
                    'status' => 'test_initial_state',
                ],
                'sorting' => [
                    'created_at' => 'ASC',
                ],
                'expectedSspInquiries' => [
                    [
                        'type' => 'test_general_type',
                        'status' => 'test_initial_state',
                    ],
                    [
                        'type' => 'test_order_type',
                        'status' => 'test_initial_state',
                    ],
                ],
            ],
        ];
    }
}
