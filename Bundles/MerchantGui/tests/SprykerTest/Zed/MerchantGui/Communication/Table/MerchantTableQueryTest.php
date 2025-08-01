<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantGui\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantGui
 * @group Communication
 * @group Table
 * @group MerchantTableQueryTest
 * Add your own group annotations below this line
 */
class MerchantTableQueryTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     *
     * @var string
     */
    protected const SERVICE_FORM_FACTORY = 'form.factory';

    /**
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var \SprykerTest\Zed\MerchantGui\MerchantGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerTwigServiceMock();
        $this->registerFormFactoryServiceMock();
        $this->registerRequestStack();
    }

    /**
     * @return void
     */
    public function testFetchDataCollectsCorrectMerchantData(): void
    {
        // Arrange
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdStores([$this->tester->getStore()->getIdStore()]);
        $merchantTransfer1 = $this->tester->haveMerchant([
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
        ]);
        $merchantTransfer2 = $this->tester->haveMerchant([
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
        ]);
        $merchantTableMock = new MerchantTableMock(
            SpyMerchantQuery::create(),
            $this->getMerchantGuiToMerchantFacadeMock(),
            [],
            [],
            [],
            [],
        );

        // Act
        $result = $merchantTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $resultMerchantIds = array_column($result, SpyMerchantTableMap::COL_ID_MERCHANT);
        $this->assertContains((string)$merchantTransfer1->getIdMerchant(), $resultMerchantIds);
        $this->assertContains((string)$merchantTransfer2->getIdMerchant(), $resultMerchantIds);
    }

    /**
     * @dataProvider merchantsDataProvider
     *
     * @param string $dataKey
     * @param array $merchantTableCriteriaTransferData
     *
     * @return void
     */
    public function testFetchDataCollectsCorrectMerchantDataByFilters(string $dataKey, array $merchantTableCriteriaTransferData = []): void
    {
        // Arrange
        $expectedMerchantIds = $this->merchantsDataProviderData()[$dataKey];
        $merchantTableMock = new MerchantTableMock(
            SpyMerchantQuery::create(),
            $this->getMerchantGuiToMerchantFacadeMock(),
            [],
            [],
            [],
            [],
        );

        $merchantTableCriteriaTransfer = $this->tester->createMerchantTableCriteriaTransfer($merchantTableCriteriaTransferData);

        // Act
        $merchantTableMock->applyCriteria($merchantTableCriteriaTransfer);
        $resultData = $merchantTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($resultData);
        $resultMerchantIds = array_column($resultData, SpyMerchantTableMap::COL_ID_MERCHANT);
        $diff = array_diff($expectedMerchantIds, $resultMerchantIds);
        $this->assertEmpty($diff);
    }

    /**
     * @return array<string, array>
     */
    protected function merchantsDataProvider(): array
    {
        return [
            'Filter by Status' => [
                'Filter by Status',
                [
                    MerchantTableCriteriaTransfer::STATUS => 1,
                ],
            ],
            'Filter by Approval Status' => [
                'Filter by Approval Status',
                [
                    MerchantTableCriteriaTransfer::APPROVAL_STATUSES => [static::STATUS_APPROVED],
                ],
            ],
            'Filter by Stores' => [
                'Filter by Stores',
                [
                    MerchantTableCriteriaTransfer::STORES => [static::STORE_NAME_AT],
                ],
            ],
            'Filter by Different Fields' => [
                'Different Fields',
                [
                    MerchantTableCriteriaTransfer::STATUS => 0,
                    MerchantTableCriteriaTransfer::APPROVAL_STATUSES => [static::STATUS_WAITING_FOR_APPROVAL],
                ],
            ],
        ];
    }

    /**
     * @return array<string, array>
     */
    protected function merchantsDataProviderData(): array
    {
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $multiStoreRelationTransfer = (new StoreRelationTransfer())
            ->addStores($storeTransferDE)
            ->addStores($storeTransferAT)
            ->setIdStores([$storeTransferDE->getIdStore(), $storeTransferAT->getIdStore()]);
        $storeRelationTransfer = (new StoreRelationTransfer())
            ->addStores($storeTransferDE)
            ->setIdStores([$storeTransferDE->getIdStore()]);
        $merchantTransfer1 = $this->tester->haveMerchant([
            MerchantTransfer::STORE_RELATION => $multiStoreRelationTransfer->toArray(),
            MerchantTransfer::STATUS => static::STATUS_APPROVED,
            MerchantTransfer::IS_ACTIVE => 1,
        ]);
        $merchantTransfer2 = $this->tester->haveMerchant([
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
            MerchantTransfer::STATUS => static::STATUS_WAITING_FOR_APPROVAL,
            MerchantTransfer::IS_ACTIVE => 1,
        ]);
        $merchantTransfer3 = $this->tester->haveMerchant([
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
            MerchantTransfer::STATUS => static::STATUS_APPROVED,
            MerchantTransfer::IS_ACTIVE => 0,
        ]);
        $merchantTransfer4 = $this->tester->haveMerchant([
            MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
            MerchantTransfer::STATUS => static::STATUS_WAITING_FOR_APPROVAL,
            MerchantTransfer::IS_ACTIVE => 0,
        ]);

        return [
            'Filter by Status' => [$merchantTransfer1->getIdMerchant(), $merchantTransfer2->getIdMerchant()],
            'Filter by Approval Status' => [$merchantTransfer1->getIdMerchant(), $merchantTransfer3->getIdMerchant()],
            'Filter by Stores' => [$merchantTransfer1->getIdMerchant()],
            'Different Fields' => [$merchantTransfer4->getIdMerchant()],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected function getMerchantGuiToMerchantFacadeMock(): MerchantGuiToMerchantFacadeInterface
    {
        return $this->getMockBuilder(MerchantGuiToMerchantFacadeInterface::class)->getMock();
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return void
     */
    protected function registerFormFactoryServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_FORM_FACTORY, $this->getFormFactoryMock());
    }

    /**
     * @return void
     */
    protected function registerRequestStack(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(Request::create('/'));
        $this->tester->getContainer()->set(static::SERVICE_REQUEST_STACK, $requestStack);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected function getTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->method('render')
            ->willReturn('Fully rendered template');
        $twigMock->method('getLoader')->willReturn($this->getChainLoader());

        return $twigMock;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     */
    protected function getChainLoader(): LoaderInterface
    {
        return new ChainLoader();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactoryMock(): FormFactoryInterface
    {
        $formFactoryMock = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $formFactoryMock->method('create')->willReturn($this->getFormMock());
        $formFactoryMock->method('createNamed')->willReturn($this->getFormMock());

        return $formFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormInterface
     */
    protected function getFormMock(): FormInterface
    {
        $formMock = $this->getMockBuilder(FormInterface::class)->getMock();
        $formMock->method('createView')->willReturn($this->getFormViewMock());

        return $formMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormView
     */
    protected function getFormViewMock(): FormView
    {
        return $this->getMockBuilder(FormView::class)->getMock();
    }
}
