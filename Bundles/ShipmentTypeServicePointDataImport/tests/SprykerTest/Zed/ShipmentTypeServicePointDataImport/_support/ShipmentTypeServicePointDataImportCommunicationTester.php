<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePointDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceType;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType\ServiceTypeKeyToServiceTypeUuidStep;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType\ShipmentTypeKeyToShipmentTypeUuidStep;
use SprykerTest\Shared\Testify\Helper\StaticVariablesHelper;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentTypeServicePointDataImportCommunicationTester extends Actor
{
    use _generated\ShipmentTypeServicePointDataImportCommunicationTesterActions;
    use StaticVariablesHelper;

    /**
     * @param string $shipmentTypeUuid
     * @param string $serviceTypeUuid
     *
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceType|null
     */
    public function findShipmentTypeServiceType(string $shipmentTypeUuid, string $serviceTypeUuid): ?SpyShipmentTypeServiceType
    {
        return $this->getShipmentTypeServiceTypeQuery()
            ->filterByShipmentTypeUuid($shipmentTypeUuid)
            ->filterByServiceTypeUuid($serviceTypeUuid)
            ->findOne();
    }

    /**
     * @return void
     */
    public function cleanUpTestData(): void
    {
        $this->cleanupStaticCache(ShipmentTypeKeyToShipmentTypeUuidStep::class, 'shipmentTypeUuidsIndexedByShipmentTypeKey', []);
        $this->cleanupStaticCache(ServiceTypeKeyToServiceTypeUuidStep::class, 'serviceTypeUuidsIndexedByServiceTypeKey', []);

        $this->getShipmentTypeQuery()
            ->filterByKey_Like('test-shipment-type-key-*')
            ->delete();

        $this->getServiceTypeQuery()
            ->filterByKey_Like('test-service-type-key-*')
            ->delete();

        $this->ensureShipmentTypeServiceTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    public function ensureShipmentTypeServiceTypeTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getShipmentTypeServiceTypeQuery());
    }

    /**
     * @param string $datasetFileName
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function createDataImporterConfigurationTransfer(string $datasetFileName): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName($datasetFileName);

        return (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
    }

    /**
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    public function getShipmentTypeServiceTypeQuery(): SpyShipmentTypeServiceTypeQuery
    {
        return SpyShipmentTypeServiceTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}
