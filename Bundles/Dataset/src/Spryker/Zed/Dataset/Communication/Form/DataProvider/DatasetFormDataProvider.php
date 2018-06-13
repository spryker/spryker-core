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
use Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface;

class DatasetFormDataProvider
{
    const FK_LOCALE_KEY = 'fkLocale';

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface $repository
     * @param \Spryker\Zed\Dataset\Dependency\Facade\DatasetToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        DatasetRepositoryInterface $repository,
        DatasetToLocaleFacadeInterface $localeFacade
    ) {
        $this->repository = $repository;
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
            return $this->createSpyDatasetTransfer();
        }
        $datasetTransfer = $this->repository->getDatasetByIdWithRelation($idDataset);

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
            DatasetForm::DATASET_HAS_DATA => $this->repository->existsDatasetById($idDataset),
        ];
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
    protected function createSpyDatasetTransfer()
    {
        $datasetTransfer = new SpyDatasetEntityTransfer();
        foreach ($this->getAvailableLocales() as $locale) {
            $localEntityTransfer = new SpyLocaleEntityTransfer();
            $localEntityTransfer->fromArray($locale->toArray());
            $datasetLocalizedAttributeTransfer = new SpyDatasetLocalizedAttributesEntityTransfer();
            $datasetLocalizedAttributeTransfer->setLocale($localEntityTransfer);
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
