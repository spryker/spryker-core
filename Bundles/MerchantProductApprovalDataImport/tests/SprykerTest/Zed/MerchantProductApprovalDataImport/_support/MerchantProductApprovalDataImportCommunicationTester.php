<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductApprovalDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;

/**
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
class MerchantProductApprovalDataImportCommunicationTester extends Actor
{
    use _generated\MerchantProductApprovalDataImportCommunicationTesterActions;

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
     * @param array<string> $references
     *
     * @return void
     */
    public function deleteMerchantByReferences(array $references): void
    {
        $this->getMerchantPropelQuery()->filterByMerchantReference_In($references)->delete();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantPropelQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }

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
     * @param array<string, string> $expectedDataSet
     *
     * @return void
     */
    public function assertDefaultProductAbstractApprovalStatuses(array $expectedDataSet): void
    {
        foreach ($expectedDataSet as $merchantReference => $approvalStatus) {
            $merchantEntity = $this->getMerchantPropelQuery()
                ->filterByMerchantReference($merchantReference)
                ->filterByDefaultProductAbstractApprovalStatus($approvalStatus)
                ->findOne();

            $this->assertNotNull($merchantEntity);
        }
    }
}
