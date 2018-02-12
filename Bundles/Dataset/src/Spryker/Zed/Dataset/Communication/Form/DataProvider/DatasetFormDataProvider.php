<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyLocaleEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Spryker\Zed\Dataset\Communication\Form\DatasetForm;
use Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface;
use Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface;

class DatasetFormDataProvider
{
    const FK_LOCALE_KEY = 'fkLocale';

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        DatasetQueryContainerInterface $queryContainer,
        DatasetToLocaleFacadeInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getData($idDataset = null)
    {
        if ($idDataset === null) {
            return $this->createEmptyspyDatasetTransfer();
        }
        $dataset = $this->queryContainer->queryDatasetById($idDataset)->findOne();
        $datasetTransfer = $this->createEmptyspyDatasetTransfer();
        $this->addSpyDatasetLocalizedAttributeTransfers($dataset, $datasetTransfer);
        $datasetTransfer->fromArray($dataset->toArray(), true);

        return $datasetTransfer;
    }

    /**
     * @param int $idDataset
     *
     * @return array
     */
    public function getOptions($idDataset)
    {
        return [
            DatasetForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
            DatasetForm::DATASET_HAS_DATA => $this->hasDatasetData($idDataset),
        ];
    }

    /**
     * @param int $idDataset
     *
     * @return bool
     */
    protected function hasDatasetData($idDataset)
    {
        if ($idDataset === null) {
            return false;
        }
        $dataset = $this->queryContainer->queryDataseWithValuesById($idDataset)->find()->getFirst();

        if ($dataset->getSpyDatasetRowColumnValues()->count()) {
            return true;
        }

        return false;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales()
    {
        return $this->localeFacade->getLocaleCollection();
    }

    /**
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    protected function createEmptyspyDatasetTransfer()
    {
        $datasetTransfer = new SpyDatasetEntityTransfer();
        foreach ($this->getAvailableLocales() as $locale) {
            $spyLocalEntityTransfer = new SpyLocaleEntityTransfer();
            $spyLocalEntityTransfer->fromArray($locale->toArray());
            $datasetLocalizedAttributeTransfer = new SpyDatasetLocalizedAttributesEntityTransfer();
            $datasetLocalizedAttributeTransfer->setLocale($spyLocalEntityTransfer);
            $datasetTransfer->addSpyDatasetLocalizedAttributess($datasetLocalizedAttributeTransfer);
        }

        return $datasetTransfer;
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return void
     */
    protected function addSpyDatasetLocalizedAttributeTransfers(
        SpyDataset $dataset,
        SpyDatasetEntityTransfer $datasetTransfer
    ) {
        /**
         * @var \Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributes[] $savedLocalizedAttributes
         */
        $savedLocalizedAttributes = $dataset->getSpyDatasetLocalizedAttributess()
            ->toKeyIndex(static::FK_LOCALE_KEY);
        foreach ($datasetTransfer->getSpyDatasetLocalizedAttributess() as $datasetLocalizedAttributeTransfer) {
            $fkLocale = $datasetLocalizedAttributeTransfer->getLocale()->getIdLocale();
            if (!empty($savedLocalizedAttributes[$fkLocale])) {
                $datasetLocalizedAttributeTransfer->fromArray($savedLocalizedAttributes[$fkLocale]->toArray());
            }
        }
    }
}
