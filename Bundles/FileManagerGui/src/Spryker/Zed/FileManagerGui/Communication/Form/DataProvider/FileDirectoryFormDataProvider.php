<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Spryker\Zed\FileManagerGui\Communication\Form\FileDirectoryForm;
use Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface;
use Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface;

class FileDirectoryFormDataProvider
{
    const FK_LOCALE_KEY = 'fkLocale';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface
     */
    protected $fileManagerFacade;

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToFileManagerFacadeInterface $fileManagerFacade
     * @param \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        FileManagerGuiToFileManagerFacadeInterface $fileManagerFacade,
        FileManagerGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->fileManagerFacade = $fileManagerFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idFileDirectory
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    public function getData($idFileDirectory = null)
    {
        if ($idFileDirectory === null) {
            return $this->createEmptyFileDirectoryTransfer();
        }

        $fileDirectoryTransfer = $this->fileManagerFacade->findFileDirectory($idFileDirectory);
        $this->setFileDirectoryLocalizedAttributes($fileDirectoryTransfer);

        return $fileDirectoryTransfer;
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
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return void
     */
    protected function setFileDirectoryLocalizedAttributes(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $locales = $this->getTransformedAvailableLocales($this->getAvailableLocales());

        foreach ($fileDirectoryTransfer->getFileDirectoryLocalizedAttributes() as $attribute) {
            if (isset($locales[$attribute->getFkLocale()])) {
                $attribute->setLocale($locales[$attribute->getFkLocale()]);
            }
        }
    }

    /**
     * @param array $locales
     *
     * @return array
     */
    protected function getTransformedAvailableLocales(array $locales)
    {
        $transformed = [];

        foreach ($locales as $locale) {
            $transformed[$locale->getIdLocale()] = $locale;
        }

        return $transformed;
    }
}
