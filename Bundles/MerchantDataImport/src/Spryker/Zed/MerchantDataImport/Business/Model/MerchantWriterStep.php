<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Model;

use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantDataImport\Business\Model\DataSet\MerchantDataSetInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;

class MerchantWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantDataSetInterface::MERCHANT_REFERENCE,
        MerchantDataSetInterface::NAME,
        MerchantDataSetInterface::REGISTRATION_NUMBER,
        MerchantDataSetInterface::STATUS,
        MerchantDataSetInterface::EMAIL,
    ];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(DataImportToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantEntity = SpyMerchantQuery::create()
            ->filterByMerchantReference($dataSet[MerchantDataSetInterface::MERCHANT_REFERENCE])
            ->findOneOrCreate();

        $merchantEntity
            ->setName($dataSet[MerchantDataSetInterface::NAME])
            ->setRegistrationNumber($dataSet[MerchantDataSetInterface::REGISTRATION_NUMBER])
            ->setStatus($dataSet[MerchantDataSetInterface::STATUS])
            ->setEmail($dataSet[MerchantDataSetInterface::EMAIL])
            ->setIsActive($dataSet[MerchantDataSetInterface::IS_ACTIVE])
            ->save();

        $merchantEntity = $this->saveGlossaryKeyAttributes($merchantEntity, $dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES]);
        $merchantEntity->save();

        $this->addPublishEvents(MerchantEvents::MERCHANT_PUBLISH, $merchantEntity->getIdMerchant());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            $this->validateRequireDataSetByKey($dataSet, $requiredDataSetKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $requiredDataSetKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateRequireDataSetByKey(DataSetInterface $dataSet, string $requiredDataSetKey): void
    {
        if (!$dataSet[$requiredDataSetKey]) {
            throw new InvalidDataException('"' . $requiredDataSetKey . '" is required.');
        }
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     * @param array $glossaryKeyAttributes
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant
     */
    protected function saveGlossaryKeyAttributes(SpyMerchant $merchantEntity, array $glossaryKeyAttributes): SpyMerchant
    {
        foreach ($glossaryKeyAttributes as $idLocale => $attributes) {
            foreach ($attributes as $attributeName => $attributeValue) {
                if ($attributeValue && $attributeName === MerchantDataSetInterface::URL) {
                    $this->addMerchantUrl($merchantEntity->getIdMerchant(), $idLocale, $attributeValue);
                }
            }
        }

        return $merchantEntity;
    }

    /**
     * @param int $idMerchant
     * @param int $idLocale
     * @param string $url
     *
     * @return void
     */
    protected function addMerchantUrl(int $idMerchant, int $idLocale, string $url): void
    {
        $urlEntity = SpyUrlQuery::create()
            ->filterByFkResourceMerchant($idMerchant)
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $urlEntity->setUrl($url);

        $urlEntity->save();

        $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
    }
}
