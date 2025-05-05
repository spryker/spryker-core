<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Form\DataProvider;

use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig as SharedSspFileManagementConfig;
use SprykerFeature\Yves\SspFileManagement\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig;

class FileSearchFilterFormDataProvider
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PREFIX_ENTITY_TYPE = 'ssp_file_management.file_management.file_search_filter_form.field.type';

    /**
     * @var array<string, string>
     */
    protected const ENTITY_TO_PERMISSION_MAP = [
        SharedSspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => ViewCompanyUserFilesPermissionPlugin::KEY,
        SharedSspFileManagementConfig::ENTITY_TYPE_COMPANY => ViewCompanyFilesPermissionPlugin::KEY,
        SharedSspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => ViewCompanyBusinessUnitFilesPermissionPlugin::KEY,
    ];

    /**
     * @param \SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig $sspFileManagementConfig
     * @param \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        protected SspFileManagementConfig $sspFileManagementConfig,
        protected GlossaryStorageClientInterface $glossaryStorageClient
    ) {
    }

    /**
     * @param string $localeName
     *
     * @return array<string, array<string, string>>
     */
    public function getOptions(string $localeName): array
    {
        return [
            FileSearchFilterForm::OPTION_FILE_TYPES => $this->getFileTypesChoices(),
            FileSearchFilterForm::OPTION_ACCESS_LEVELS => $this->getAccessLevelChoices($localeName),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getFileTypesChoices(): array
    {
        return array_combine(
            $this->sspFileManagementConfig->getAllowedFileTypes(),
            $this->sspFileManagementConfig->getAllowedFileTypes(),
        );
    }

    /**
     * @param string $localeName
     *
     * @return array<string, string>
     */
    protected function getAccessLevelChoices(string $localeName): array
    {
        $accessLevelChoices = [];
        $translationKeys = [];

        foreach ($this->sspFileManagementConfig->getEntityTypes() as $entityType) {
            $translationKeys[] = $this->getTranslationKey($entityType);
        }

        $translatedEntityTypes = $this->glossaryStorageClient->translateBulk($translationKeys, $localeName);
        foreach ($this->sspFileManagementConfig->getEntityTypes() as $entityType) {
            if (isset(static::ENTITY_TO_PERMISSION_MAP[$entityType]) && !$this->can(static::ENTITY_TO_PERMISSION_MAP[$entityType])) {
                continue;
            }

            $accessLevelChoices[$entityType] = $translatedEntityTypes[$this->getTranslationKey($entityType)] ?? $entityType;
        }

        return $accessLevelChoices;
    }

    /**
     * @param string $entityType
     *
     * @return string
     */
    protected function getTranslationKey(string $entityType): string
    {
        return sprintf('%s.%s', static::GLOSSARY_KEY_PREFIX_ENTITY_TYPE, $entityType);
    }
}
