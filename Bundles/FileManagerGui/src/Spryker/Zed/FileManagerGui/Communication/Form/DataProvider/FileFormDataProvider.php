<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Spryker\Zed\Cms\Communication\Form\CmsPageFormType;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;

class FileFormDataProvider
{
    /**
     * @var FileManagerQueryContainer
     */
    private $queryContainer;

    /**
     * @param FileManagerQueryContainer $queryContainer
     */
    public function __construct(
        FileManagerQueryContainer $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int|null $idFile
     * @return array
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function getData($idFile = null)
    {
        if ($idFile === null) {
            return [];
        }

        $file = $this
            ->queryContainer
            ->queryFileById($idFile)
            ->findOne();

        return [
            FileForm::FIELD_FILE_NAME => $file->getFileName(),
        ];
    }

}
