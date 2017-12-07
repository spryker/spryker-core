<?php

namespace SprykerTest\Zed\ProductManagementStoreConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductManagementStoreConnector
 * @group Business
 * @group Facade
 * @group ProductManagementStoreConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductManagementStoreConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductManagementStoreConnector\ProductManagementStoreConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductAbstractStoreRelationRetrievesRelatedStores()
    {
        // Assign
        $idProductAbstract = 1;
        $relatedStores = [1, 3];
        $productAbstractRelationRequest = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract);
        $expectedResult = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract)
            ->setIdStores($relatedStores);

        $this->getProductManagementStoreConnectorFacade()->saveProductAbstractStoreRelation($expectedResult);

        // Act
        $actualResult = $this
            ->getProductManagementStoreConnectorFacade()
            ->getProductAbstractStoreRelation($productAbstractRelationRequest);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider relationUpdate
     *
     * @reutrn void
     *
     * @param int[] $originalRelation
     * @param int[] $modifiedRelation
     *
     * @return void
     */
    public function testSaveProductAbstractStoreRelation(array $originalRelation, array $modifiedRelation)
    {
        // Assign
        $idProductAbstract = 1;
        $productAbstractRelationRequest = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract);
        $originalRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract)
            ->setIdStores($originalRelation);
        $modifiedRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($idProductAbstract)
            ->setIdStores($modifiedRelation);

        $this->getProductManagementStoreConnectorFacade()->saveProductAbstractStoreRelation($originalRelationTransfer);

        // Act
        $beforeSaveIdStores = $this
            ->getProductManagementStoreConnectorFacade()
            ->getProductAbstractStoreRelation($productAbstractRelationRequest)
            ->getIdStores();
        $this->getProductManagementStoreConnectorFacade()->saveProductAbstractStoreRelation($modifiedRelationTransfer);
        $afterSaveIdStores = $this
            ->getProductManagementStoreConnectorFacade()
            ->getProductAbstractStoreRelation($productAbstractRelationRequest)
            ->getIdStores();

        // Assert
        sort($beforeSaveIdStores);
        sort($afterSaveIdStores);
        $this->assertEquals($originalRelation, $beforeSaveIdStores);
        $this->assertEquals($modifiedRelation, $afterSaveIdStores);
    }

    /**
     * @return array
     */
    public function relationUpdate()
    {
        return [
            [
                [1, 2, 3], [2],
            ],
            [
                [1], [1, 2],
            ],
            [
                [2], [1, 3],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\ProductManagementStoreConnector\Business\ProductManagementStoreConnectorFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getProductManagementStoreConnectorFacade()
    {
        return $this->tester->getLocator()->productManagementStoreConnector()->facade();
    }
}
