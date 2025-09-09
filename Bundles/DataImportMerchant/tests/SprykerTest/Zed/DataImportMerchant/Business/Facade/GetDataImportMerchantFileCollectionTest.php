<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImportMerchant\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileConditionsTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileSearchConditionsTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface;
use SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImportMerchant
 * @group Business
 * @group Facade
 * @group GetDataImportMerchantFileCollectionTest
 * Add your own group annotations below this line
 */
class GetDataImportMerchantFileCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester
     */
    protected DataImportMerchantBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDataImportMerchantTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionReturnsEmptyCollectionWhenNoDataExists(): void
    {
        // Arrange
        $dataImportMerchantFileCriteriaTransfer = new DataImportMerchantFileCriteriaTransfer();

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(0, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionReturnsAllDataImportMerchantFilesWhenNoCriteriaProvided(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());

        $dataImportMerchantFileCriteriaTransfer = new DataImportMerchantFileCriteriaTransfer();

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(2, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByDataImportMerchantFileIds(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->toArray(),
        );

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addIdDataImportMerchantFile($dataImportMerchantFileTransfer->getIdDataImportMerchantFile());

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(
            $dataImportMerchantFileTransfer->getIdDataImportMerchantFile(),
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getIterator()->current()->getIdDataImportMerchantFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByUuids(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->toArray(),
        );

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addUuid($dataImportMerchantFileTransfer->getUuid());

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(
            $dataImportMerchantFileTransfer->getIdDataImportMerchantFile(),
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getIterator()->current()->getIdDataImportMerchantFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByStatuses(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus('new')->toArray(),
        );
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setStatus('pending')->toArray(),
        );

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addStatus('pending');

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(
            $dataImportMerchantFileTransfer->getIdDataImportMerchantFile(),
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getIterator()->current()->getIdDataImportMerchantFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByImporterTypes(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setImporterType('product')->toArray(),
        );
        $dataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile(
            $this->tester->createValidDataImportMerchantFile()->setImporterType('merchant-price')->toArray(),
        );

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addImporterType('merchant-price');

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(
            $dataImportMerchantFileTransfer->getIdDataImportMerchantFile(),
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getIterator()->current()->getIdDataImportMerchantFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByMerchantReference(): void
    {
        // Arrange
        $merchantReference1 = $this->tester->haveMerchant()->getMerchantReference();
        $merchantReference2 = $this->tester->haveMerchant()->getMerchantReference();

        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile($merchantReference1)->toArray());
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile($merchantReference2)->toArray());

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addMerchantReference($merchantReference2);

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByUserIds(): void
    {
        // Arrange
        $idUser1 = $this->tester->haveUser()->getIdUser();
        $idUser2 = $this->tester->haveUser()->getIdUser();

        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile(null, $idUser1)->toArray());
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile(null, $idUser2)->toArray());

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->addIdUser($idUser1);

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionAppliesSorting(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile('AAA')->toArray());
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile('ZZZ')->toArray());

        $sortTransfer = (new SortTransfer())
            ->setField(DataImportMerchantFileTransfer::MERCHANT_REFERENCE)
            ->setIsAscending(false);

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->addSort($sortTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $dataImportMerchantFileTransfers = $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getArrayCopy();
        $this->assertSame('ZZZ', $dataImportMerchantFileTransfers[0]->getMerchantReference());
        $this->assertSame('AAA', $dataImportMerchantFileTransfers[1]->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionAppliesPagination(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage(2);

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setPagination($paginationTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(2, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(3, $dataImportMerchantFileCollectionTransfer->getPagination()->getNbResults());
        $this->assertSame(1, $dataImportMerchantFileCollectionTransfer->getPagination()->getPage());
        $this->assertSame(2, $dataImportMerchantFileCollectionTransfer->getPagination()->getMaxPerPage());
    }

    /**
     * @return void
     */
    public function testShouldExecuteDataImportMerchantFileExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->haveDataImportMerchantFile($this->tester->createValidDataImportMerchantFile()->toArray());

        // Assert
        $dataImportMerchantFileExpanderPluginMock = $this->createMock(DataImportMerchantFileExpanderPluginInterface::class);
        $dataImportMerchantFileExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturnArgument(0);

        $this->tester->setDependency(
            DataImportMerchantDependencyProvider::PLUGINS_DATA_IMPORT_MERCHANT_FILE_EXPANDER,
            [$dataImportMerchantFileExpanderPluginMock],
        );

        // Act
        $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection(new DataImportMerchantFileCriteriaTransfer());
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByOriginalFileName(): void
    {
        // Arrange
        $firstFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $firstFileTransfer->getFileInfoOrFail()->setOriginalFileName('products.csv');
        $this->tester->haveDataImportMerchantFile($firstFileTransfer->toArray());

        $secondFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $secondFileTransfer->getFileInfoOrFail()->setOriginalFileName('orders.csv');
        $targetDataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile($secondFileTransfer->toArray());

        $dataImportMerchantFileSearchConditionsTransfer = (new DataImportMerchantFileSearchConditionsTransfer())
            ->setOriginalFileName('der');

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileSearchConditions($dataImportMerchantFileSearchConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(
            $targetDataImportMerchantFileTransfer->getIdDataImportMerchantFile(),
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getIterator()->current()->getIdDataImportMerchantFile(),
        );
    }

    /**
     * @return void
     */
    public function testGetDataImportMerchantFileCollectionFiltersByCreatedAtRange(): void
    {
        // Arrange
        $oldFileTransfer = $this->tester->createValidDataImportMerchantFile()
            ->setCreatedAt(date('Y-m-d H:i:s', time() - 1000));
        $this->tester->haveDataImportMerchantFile($oldFileTransfer->toArray());

        $rangeCreatedAtTransfer = (new CriteriaRangeFilterTransfer())
            ->setFrom(date('Y-m-d H:i:s', time()));

        $recentFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $targetDataImportMerchantFileTransfer = $this->tester->haveDataImportMerchantFile($recentFileTransfer->toArray());

        $dataImportMerchantFileConditionsTransfer = (new DataImportMerchantFileConditionsTransfer())
            ->setRangeCreatedAt($rangeCreatedAtTransfer);

        $dataImportMerchantFileCriteriaTransfer = (new DataImportMerchantFileCriteriaTransfer())
            ->setDataImportMerchantFileConditions($dataImportMerchantFileConditionsTransfer);

        // Act
        $dataImportMerchantFileCollectionTransfer = $this->tester
            ->getFacade()
            ->getDataImportMerchantFileCollection($dataImportMerchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles());
        $this->assertSame(
            $targetDataImportMerchantFileTransfer->getIdDataImportMerchantFile(),
            $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles()->getIterator()->current()->getIdDataImportMerchantFile(),
        );
    }
}
