<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryForm;
use Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;

class FileDirectoryFormDataProvider
{
    const FK_LOCALE_KEY = 'fkLocale';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        FileManagerGuiToFileManagerQueryContainerInterface $queryContainer,
        FileManagerGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idFile
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    public function getData($idFile = null)
    {
        if ($idFile === null) {
            return $this->createEmptyFileDirectoryTransfer();
        }

        $file = $this
            ->queryContainer
            ->queryFileById($idFile)
            ->findOne();

        $fileDirectoryTransfer = $this->createEmptyFileDirectoryTransfer();

        $this->addFileDirectoryLocalizedAttributes($file, $fileDirectoryTransfer);
        $fileTransfer->fromArray($file->toArray());

        return $fileTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            FileDirectoryForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
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
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    protected function createEmptyFileDirectoryTransfer()
    {
        $fileDirectoryTransfer = new FileDirectoryTransfer();

        foreach ($this->getAvailableLocales() as $locale) {
            $fileLocalizedAttribute = new FileDirectoryLocalizedAttributesTransfer();
            $fileLocalizedAttribute->setLocale($locale);

            $fileDirectoryTransfer->addFileDirectoryLocalizedAttribute($fileLocalizedAttribute);
        }

        return $fileDirectoryTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function addFileDirectoryLocalizedAttributes(SpyFileDirectory $file, FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $savedLocalizedAttributes = $file->getSpyFileLocalizedAttributess()
            ->toKeyIndex(static::FK_LOCALE_KEY);

        foreach ($fileTransfer->getFileLocalizedAttributes() as $fileLocalizedAttribute) {
            $fkLocale = $fileLocalizedAttribute->getLocale()->getIdLocale();

            if (!empty($savedLocalizedAttributes[$fkLocale])) {
                $fileLocalizedAttribute->fromArray($savedLocalizedAttributes[$fkLocale]->toArray());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    protected function setTranslationFields(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $fileDirectoryLocalizedAttributesTransfer = new FileDirectoryLocalizedAttributesTransfer();
            $fileDirectoryLocalizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
            $fileDirectoryTransfer->addFileDirectoryLocalizedAttribute($fileDirectoryLocalizedAttributesTransfer);
        }

        return $fileDirectoryTransfer;
    }
}
