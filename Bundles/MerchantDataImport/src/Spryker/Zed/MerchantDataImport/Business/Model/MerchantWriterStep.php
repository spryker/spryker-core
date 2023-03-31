<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\Model;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantDataImport\Business\Model\DataSet\MerchantDataSetInterface;
use Spryker\Zed\MerchantDataImport\Dependency\Facade\MerchantDataImportToMerchantFacadeInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;

class MerchantWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantDataSetInterface::MERCHANT_REFERENCE,
        MerchantDataSetInterface::NAME,
        MerchantDataSetInterface::REGISTRATION_NUMBER,
        MerchantDataSetInterface::STATUS,
        MerchantDataSetInterface::EMAIL,
    ];

    /**
     * @var string
     */
    protected const ACTION_CREATE = 'create';

    /**
     * @var string
     */
    protected const ACTION_UPDATE = 'update';

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\MerchantDataImport\Dependency\Facade\MerchantDataImportToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\MerchantDataImport\Dependency\Facade\MerchantDataImportToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        MerchantDataImportToMerchantFacadeInterface $merchantFacade
    ) {
        $this->eventFacade = $eventFacade;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReference($dataSet[MerchantDataSetInterface::MERCHANT_REFERENCE]);
        $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);
        $action = $merchantTransfer ? static::ACTION_UPDATE : static::ACTION_CREATE;

        if (!$merchantTransfer) {
            $merchantTransfer = new MerchantTransfer();
        }

        $merchantTransfer
            ->setMerchantReference($dataSet[MerchantDataSetInterface::MERCHANT_REFERENCE])
            ->setName($dataSet[MerchantDataSetInterface::NAME])
            ->setRegistrationNumber($dataSet[MerchantDataSetInterface::REGISTRATION_NUMBER])
            ->setStatus($dataSet[MerchantDataSetInterface::STATUS])
            ->setEmail($dataSet[MerchantDataSetInterface::EMAIL])
            ->setIsActive($dataSet[MerchantDataSetInterface::IS_ACTIVE]);

        $merchantResponseTransfer = null;
        if ($action === static::ACTION_CREATE) {
            $merchantTransfer->setStoreRelation(new StoreRelationTransfer())
                ->setMerchantProfile(new MerchantProfileTransfer());

            $merchantResponseTransfer = $this->merchantFacade->createMerchant($merchantTransfer);
        }

        if ($action === static::ACTION_UPDATE) {
            $merchantResponseTransfer = $this->merchantFacade->updateMerchant($merchantTransfer);
        }

        if ($merchantResponseTransfer === null || !$merchantResponseTransfer->getMerchant()) {
            return;
        }

        if ($merchantResponseTransfer->getErrors()->count() > 0) {
            $merchantErrorTransfers = $merchantResponseTransfer->getErrors();
            $errorMessage = $merchantErrorTransfers->getIterator()->current()->getMessage();

            throw new InvalidDataException($errorMessage);
        }

        $merchantTransfer = $merchantResponseTransfer->getMerchantOrFail();
        $this->saveGlossaryKeyAttributes($merchantTransfer, $dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES]);
        $this->addPublishEvents(MerchantEvents::MERCHANT_PUBLISH, $merchantTransfer->getIdMerchantOrFail());
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
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param array $glossaryKeyAttributes
     *
     * @return void
     */
    protected function saveGlossaryKeyAttributes(MerchantTransfer $merchantTransfer, array $glossaryKeyAttributes): void
    {
        foreach ($glossaryKeyAttributes as $idLocale => $attributes) {
            foreach ($attributes as $attributeName => $attributeValue) {
                if ($attributeValue && $attributeName === MerchantDataSetInterface::URL) {
                    $this->addMerchantUrl($merchantTransfer->getIdMerchantOrFail(), $idLocale, $attributeValue);
                }
            }
        }
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
        $redirectUrlEntity = $this->getUrlPropelQuery()
            ->filterByFkResourceRedirect(null, Criteria::NOT_EQUAL)
            ->filterByUrl($url)
            ->findOne();

        if ($redirectUrlEntity) {
            return;
        }

        $urlEntity = $this->getUrlPropelQuery()
            ->filterByFkResourceMerchant($idMerchant)
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $urlEntity->setUrl($url);

        $urlEntity->save();

        $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected function getUrlPropelQuery(): SpyUrlQuery
    {
        return SpyUrlQuery::create();
    }
}
