<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider;

use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\ViewFileDetailTableFilterForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class ViewFileDetailTableFilterFormDataProvider
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     * @param \Spryker\Zed\Translator\Business\TranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected TranslatorFacadeInterface $translatorFacade
    ) {
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getOptions(): array
    {
        $entityTypes = [];

        foreach ($this->selfServicePortalConfig->getEntityTypes() as $entityType) {
            $entityTypes[$this->translatorFacade->trans($entityType)] = $entityType;
        }

        return [
            ViewFileDetailTableFilterForm::OPTION_ENTITY_TYPES => $entityTypes,
        ];
    }
}
