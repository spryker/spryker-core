<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface;

class FileDirectoryFormDataProvider
{
    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\Facade\FileManagerGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(FileManagerGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\FileDirectoryTransfer
     */
    public function getData()
    {
        $fileDirectoryTransfer = new FileDirectoryTransfer();
        $fileDirectoryTransfer = $this->setTranslationFields($fileDirectoryTransfer);

        return $fileDirectoryTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
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
