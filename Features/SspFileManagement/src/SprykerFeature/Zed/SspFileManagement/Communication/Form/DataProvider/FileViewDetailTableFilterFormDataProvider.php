<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider;

use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\FileViewDetailTableFilterForm;
use SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig;

class FileViewDetailTableFilterFormDataProvider
{
    /**
     * @param \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig $SspFileManagementConfig
     * @param \Spryker\Zed\Translator\Business\TranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        protected SspFileManagementConfig $SspFileManagementConfig,
        protected TranslatorFacadeInterface $translatorFacade
    ) {
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        $entityTypes = [];

        foreach ($this->SspFileManagementConfig->getEntityTypes() as $entityType) {
            $entityTypes[$this->translatorFacade->trans($entityType)] = $entityType;
        }

        return [
            FileViewDetailTableFilterForm::OPTION_ENTITY_TYPES => $entityTypes,
        ];
    }
}
