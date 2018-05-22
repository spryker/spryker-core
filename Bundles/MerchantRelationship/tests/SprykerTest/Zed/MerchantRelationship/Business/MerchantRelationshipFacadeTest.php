<?php
namespace SprykerTest\Zed\Business\MerchantRelationship;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Business
 * @group MerchantRelationship
 * @group Facade
 * @group MerchantRelationshipFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipFacadeTest extends Unit
{
    /**
     * @var \MerchantRelationship\MerchantRelationshipBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _before()
    {
    }

    /**
     * @return void
     */
    protected function _after()
    {
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationship(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');

        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithNotUniqueKeyThrowsException(): void
    {
        $this->haveMerchantRelationship('mr-test');

        $this->expectException(Exception::class);

        $this->haveMerchantRelationship('mr-test');
    }

    /**
     * @return void
     */
    public function testUpdateMerchantRelationship(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $newMerchant = $this->tester->haveMerchant();
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit();
        $newKey = 'mr-test-1';

        $merchantRelationship
            ->setFkMerchant($newMerchant->getIdMerchant())
            ->setFkCompanyBusinessUnit($newCompanyBusinessUnit->getIdCompanyBusinessUnit())
            ->setMerchantRelationshipKey($newKey);

        $updatedMerchantRelationship = (new MerchantRelationshipFacade())
            ->updateMerchantRelationship($merchantRelationship);

        $this->assertSame($idMerchantRelationship, $updatedMerchantRelationship->getIdMerchantRelationship());
        $this->assertSame($newMerchant->getIdMerchant(), $updatedMerchantRelationship->getFkMerchant());
        $this->assertSame($newCompanyBusinessUnit->getIdCompanyBusinessUnit(), $updatedMerchantRelationship->getFkCompanyBusinessUnit());
        $this->assertSame($newKey, $updatedMerchantRelationship->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipById(): void
    {
        $expectedMerchantRelationship = $this->haveMerchantRelationship('mr-test');

        $merchantRelationship = (new MerchantRelationshipTransfer())->setIdMerchantRelationship(
            $expectedMerchantRelationship->getIdMerchantRelationship()
        );

        $actualMerchantRelationship = (new MerchantRelationshipFacade())
            ->getMerchantRelationshipById($merchantRelationship);

        $this->assertNotNull($actualMerchantRelationship->getIdMerchantRelationship());
        $this->assertEquals($expectedMerchantRelationship, $actualMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationship(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        (new MerchantRelationshipFacade())
            ->deleteMerchantRelationship($merchantRelationship);

        $this->tester->assertMerchantRelationshipNotExists($idMerchantRelationship);
    }

    /**
     * @param string $merchantRelationshipKey
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function haveMerchantRelationship(string $merchantRelationshipKey): MerchantRelationshipTransfer
    {
        $merchant = $this->tester->haveMerchant();
        $companyBusinessUnit = $this->tester->haveCompanyBusinessUnit();

        return $this->tester->haveMerchantRelationship([
            'fkMerchant' => $merchant->getIdMerchant(),
            'fkCompanyBusinessUnit' => $companyBusinessUnit->getIdCompanyBusinessUnit(),
            'merchantRelationshipKey' => $merchantRelationshipKey,
        ]);
    }
}
