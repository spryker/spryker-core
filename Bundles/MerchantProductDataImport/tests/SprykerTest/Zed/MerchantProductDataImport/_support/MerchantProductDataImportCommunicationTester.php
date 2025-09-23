<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;

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
class MerchantProductDataImportCommunicationTester extends Actor
{
    use _generated\MerchantProductDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantProductAbstractTablesIsEmpty(): void
    {
        $this->createMerchantProductAbstractPropelQuery()->deleteAll();
    }

    /**
     * @param string|null $importerType
     * @param string|null $csvHeaders
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function createDataImportMerchantFileTransfer(
        ?string $importerType = 'merchant-combined-product',
        ?string $csvHeaders = 'abstract_sku,product.assigned_product_type,product_abstract_name,product_abstract_price'
    ): DataImportMerchantFileTransfer {
        return (new DataImportMerchantFileTransfer())
            ->setImporterType($importerType)
            ->setFileInfo((new DataImportMerchantFileInfoTransfer())->setContent(implode(PHP_EOL, [$csvHeaders])));
    }

    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function createMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return SpyMerchantProductAbstractQuery::create();
    }
}
