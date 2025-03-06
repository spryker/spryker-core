<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider;

use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\FileTableFilterForm;

class FileTableFilterFormDataProvider
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery $fileInfoQuery
     */
    public function __construct(protected SpyFileInfoQuery $fileInfoQuery)
    {
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\FileManager\Persistence\SpyFileInfo> $fileInfoEntityCollection
         */
        $fileInfoEntityCollection = $this->fileInfoQuery
            ->select([SpyFileInfoTableMap::COL_EXTENSION])
            ->distinct()
            ->find();

        /** @var array<string> $extensionList */
        $extensionList = $fileInfoEntityCollection->toArray();
        $extensionOptions = array_combine($extensionList, $extensionList);

        return [
            FileTableFilterForm::OPTION_EXTENSIONS => $extensionOptions,
        ];
    }
}
