<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ProductRelationDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_PRODUCT_RELATION = 'product-relation';
    public const IMPORT_TYPE_PRODUCT_RELATION_STORE = 'product-relation-store';

    /**
     * @uses \Spryker\Shared\ProductRelationStorage\ProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_PUBLISH
     */
    public const PRODUCT_ABSTRACT_RELATION_PUBLISH = 'ProductRelation.product_abstract_relation.publish';

    /**
     * @uses \Spryker\Shared\ProductRelationStorage\ProductRelationStorageConfig::PRODUCT_ABSTRACT_RELATION_STORE_PUBLISH
     */
    public const PRODUCT_ABSTRACT_RELATION_STORE_PUBLISH = 'ProductRelation.product_abstract_relation_store.publish';

    protected const MODULE_ROOT_DIRECTORY_LEVEL = 4;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductRelationDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectoryPath();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_relation.csv', static::IMPORT_TYPE_PRODUCT_RELATION);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductRelationStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectoryPath();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'product_relation_store.csv', static::IMPORT_TYPE_PRODUCT_RELATION_STORE);
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectoryPath(): string
    {
        return $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            dirname(__DIR__, static::MODULE_ROOT_DIRECTORY_LEVEL)
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
