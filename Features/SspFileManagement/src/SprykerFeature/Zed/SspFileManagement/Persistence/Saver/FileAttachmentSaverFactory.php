<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence\Saver;

use InvalidArgumentException;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpySspAssetFileQuery;
use SprykerFeature\Shared\SspFileManagement\SspFileManagementConfig;

class FileAttachmentSaverFactory
{
    /**
     * @var array<string, string>
     */
    protected const ENTITY_MAPPING = [
        SspFileManagementConfig::ENTITY_TYPE_COMPANY => CompanyFileSaver::class,
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => CompanyUserFileSaver::class,
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => CompanyBusinessUnitFileSaver::class,
        SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET => SspAssetFileSaver::class,
    ];

    /**
     * @var array<string, string>
     */
    protected const QUERY_MAPPING = [
        SspFileManagementConfig::ENTITY_TYPE_COMPANY => 'createCompanyFileQuery',
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_USER => 'createCompanyUserFileQuery',
        SspFileManagementConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => 'createCompanyBusinessUnitFileQuery',
        SspFileManagementConfig::ENTITY_TYPE_SSP_ASSET => 'createSspAssetFileQuery',
    ];

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery
     */
    protected function createCompanyFileQuery(): SpyCompanyFileQuery
    {
        return SpyCompanyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery
     */
    protected function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery
     */
    protected function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpySspAssetFileQuery
     */
    public function createSspAssetFileQuery(): SpySspAssetFileQuery
    {
        return SpySspAssetFileQuery::create();
    }

    /**
     * @param string $entityName
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\Saver\FileAttachmentSaverInterface
     */
    public function create(string $entityName): FileAttachmentSaverInterface
    {
        if (!isset(static::ENTITY_MAPPING[$entityName], static::QUERY_MAPPING[$entityName])) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported entity type: %s',
                $entityName,
            ));
        }

        $saverClass = static::ENTITY_MAPPING[$entityName];
        $queryMethod = static::QUERY_MAPPING[$entityName];

        /** @var \SprykerFeature\Zed\SspFileManagement\Persistence\Saver\FileAttachmentSaverInterface $saver */
        $saver = new $saverClass(
            $this->{$queryMethod}(),
        );

        return $saver;
    }
}
