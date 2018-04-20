<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;

class FileFormDataProvider
{
    const FK_LOCALE_KEY = 'fkLocale';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
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
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    public function getData($idFile = null)
    {
        if ($idFile === null) {
            return $this->createEmptyFileTransfer();
        }

        $file = $this
            ->queryContainer
            ->queryFileById($idFile)
            ->findOne();

        $fileTransfer = $this->createEmptyFileTransfer();

        $this->addFileLocalizedAttributes($file, $fileTransfer);
        $fileTransfer->fromArray($file->toArray());

        return $fileTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            FileForm::OPTION_AVAILABLE_LOCALES => $this->getAvailableLocales(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getAvailableLocales()
    {
        return $this->localeFacade
            ->getLocaleCollection();
    }

    /**
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createEmptyFileTransfer()
    {
        $fileTransfer = new FileTransfer();

        foreach ($this->getAvailableLocales() as $locale) {
            $fileLocalizedAttribute = new FileLocalizedAttributesTransfer();
            $fileLocalizedAttribute->setLocale($locale);

            $fileTransfer->addLocalizedAttributes($fileLocalizedAttribute);
        }

        return $fileTransfer;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return void
     */
    protected function addFileLocalizedAttributes(SpyFile $file, FileTransfer $fileTransfer)
    {
        $savedLocalizedAttributes = $file->getSpyFileLocalizedAttributess()
            ->toKeyIndex(static::FK_LOCALE_KEY);

        foreach ($fileTransfer->getLocalizedAttributes() as $fileLocalizedAttribute) {
            $fkLocale = $fileLocalizedAttribute->getLocale()->getIdLocale();

            if (!empty($savedLocalizedAttributes[$fkLocale])) {
                $fileLocalizedAttribute->fromArray($savedLocalizedAttributes[$fkLocale]->toArray());
            }
        }
    }
}
