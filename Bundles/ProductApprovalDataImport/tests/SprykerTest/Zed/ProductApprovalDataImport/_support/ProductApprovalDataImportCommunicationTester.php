<?php
/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductApprovalDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductApprovalDataImportCommunicationTester extends Actor
{
    use _generated\ProductApprovalDataImportCommunicationTesterActions;

    /**
     * @var string
     */
    protected const APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var string
     */
    protected const APPROVAL_STATUS_DENIED = 'denied';

    /**
     * @var string
     */
    protected const APPROVAL_STATUS_DRAFT = 'draft';

    /**
     * @var array<string>
     */
    protected const ALLOWED_APPROVAL_STATUS_LIST = [
        self::APPROVAL_STATUS_APPROVED,
        self::APPROVAL_STATUS_WAITING_FOR_APPROVAL,
        self::APPROVAL_STATUS_DENIED,
        self::APPROVAL_STATUS_DRAFT,
    ];

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function createDataImporterConfigurationTransfer(string $fileName): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . $fileName);

        return (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);
    }

    /**
     * @return array<int, string>
     */
    public function getApprovalStatusAllowedValues(): array
    {
        return static::ALLOWED_APPROVAL_STATUS_LIST;
    }

    /**
     * @param array<string> $skus
     *
     * @return void
     */
    public function deleteAbstractProductsBySkus(array $skus): void
    {
        $this->createProductAbstractPropelQuery()->filterBySku_In($skus)->delete();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function createProductAbstractPropelQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @param array<int, string> $expectedDataSet
     *
     * @return void
     */
    public function assertProductApprovalStatuses(array $expectedDataSet): void
    {
        foreach ($expectedDataSet as $sku => $expectedApprovalStatus) {
            $productAbstractEntity = $this->createProductAbstractPropelQuery()
                ->filterBySku($sku)
                ->filterByApprovalStatus($expectedApprovalStatus)
                ->findOne();

            $this->assertNotNull($productAbstractEntity);
        }
    }
}
