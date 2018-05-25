<?php
namespace SprykerTest\Zed\Merchant;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Merchant\Business\MerchantFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Facade
 * @group MerchantFacadeTest
 * Add your own group annotations below this line
 */
class MerchantFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
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
    public function testCreateMerchant(): void
    {
        $merchant = (new MerchantTransfer())
            ->setMerchantKey('spryker-test-1')
            ->setName('Spryker Merchant');

        (new MerchantFacade())->createMerchant($merchant);

        $this->assertNotNull($merchant->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyKeyGeneratesKey(): void
    {
        $merchant = (new MerchantTransfer())
            ->setName('Spryker Merchant');

        (new MerchantFacade())->createMerchant($merchant);

        $this->assertNotNull($merchant->getMerchantKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyNameThrowsException(): void
    {
        $merchant = (new MerchantTransfer())
            ->setMerchantKey('spryker-test-1');

        $this->expectException(RequiredTransferPropertyException::class);

        (new MerchantFacade())->createMerchant($merchant);
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithNotUniqueName(): void
    {
        $merchant = $this->tester->haveMerchant();
        $newMerchant = (new MerchantTransfer())
            ->setMerchantKey($merchant->getMerchantKey() . '-1')
            ->setName($merchant->getName());

        (new MerchantFacade())->createMerchant($newMerchant);
        $this->assertNotNull($newMerchant->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithNotUniqueKeyThrowsException(): void
    {
        $merchant = $this->tester->haveMerchant();

        $newMerchant = (new MerchantTransfer())
            ->setMerchantKey($merchant->getMerchantKey())
            ->setName($merchant->getName());

        $this->expectException(Exception::class);

        (new MerchantFacade())->createMerchant($newMerchant);
    }

    /**
     * @return void
     */
    public function testUpdateMerchant(): void
    {
        $merchant = $this->tester->haveMerchant([
            'one-key',
            'One Company',
        ]);

        $expectedIdMerchant = $merchant->getIdMerchant();
        $merchant
            ->setMerchantKey('second-key')
            ->setName('Second Company');

        $updatedMerchant = (new MerchantFacade())->updateMerchant($merchant);

        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertEquals('second-key', $updatedMerchant->getMerchantKey());
        $this->assertEquals('Second Company', $updatedMerchant->getName());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithoutDataThrowsException(): void
    {
        $merchant = $this->tester->haveMerchant();
        $merchant
            ->setIdMerchant(null)
            ->setMerchantKey(null)
            ->setName(null);

        $this->expectException(RequiredTransferPropertyException::class);

        (new MerchantFacade())->updateMerchant($merchant);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithWrongIdThrowsException(): void
    {
        $merchant = $this->tester->haveMerchant();
        $merchant
            ->setIdMerchant($merchant->getIdMerchant() + 1);

        $this->expectException(Exception::class);

        (new MerchantFacade())->updateMerchant($merchant);
    }

    /**
     * @return void
     */
    public function testGetMerchantById(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $merchant = (new MerchantTransfer())
            ->setIdMerchant($expectedMerchant->getIdMerchant());

        $actualMerchant = (new MerchantFacade())->getMerchantById($merchant);

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testDeleteMerchant(): void
    {
        $merchant = $this->tester->haveMerchant();

        (new MerchantFacade())->deleteMerchant($merchant);

        $this->tester->assertMerchantNotExists($merchant->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantWithoutThrowsException(): void
    {
        $merchant = new MerchantTransfer();

        $this->expectException(RequiredTransferPropertyException::class);

        (new MerchantFacade())->deleteMerchant($merchant);
    }

    /**
     * @return void
     */
    public function testGetMerchantsReturnNotEmptyCollection(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        $this->tester->haveMerchant();
        $this->tester->haveMerchant();

        $merchantCollection = (new MerchantFacade())->getMerchants();
        $this->assertCount(2, $merchantCollection->getMerchants());
    }
}
