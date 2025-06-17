<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider;

use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\FileTableFilterForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class FileTableFilterFormDataProvider
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     */
    public function __construct(protected SelfServicePortalConfig $config)
    {
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        $fileExtensions = array_map(function (string $fileExtension) {
            return trim($fileExtension, '.');
        }, $this->config->getAllowedFileExtensions());

        return [
            FileTableFilterForm::OPTION_EXTENSIONS => array_combine($fileExtensions, $fileExtensions),
        ];
    }
}
