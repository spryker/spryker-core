<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity;

use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityFilePathNotDefinedException;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DynamicEntityConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ERROR_PATH = '%errorPath%';

    /**
     * @var string
     */
    public const PLACEHOLDER_FIELD_NAME = '%fieldName%';

    /**
     * Specification:
     * - Path to configuration file with Dynamic entity data in JSON format.
     * - Example:
     *  [
     *      {
     *          "tableName": "spy_country",
     *          "tableAlias": "countries",
     *          "isActive": true,
     *          "definition": {
     *              "identifier": "id_country",
     *              "fields": [
     *                  {
     *                      "fieldName": "id_country",
     *                      "fieldVisibleName": "id_country",
     *                      "isCreatable": false,
     *                      "isEditable": false,
     *                      "type": "integer",
     *                      "validation": { "isRequired": false, "min": 1, "max": 5}
     *                  },
     *                  {
     *                      "fieldName": "iso2_code",
     *                      "fieldVisibleName": "iso2_code",
     *                      "isCreatable": true,
     *                      "isEditable": true,
     *                      "type": "string",
     *                      "validation": { "isRequired": true, "minLength": 2, "maxLength": 2 }
     *                  },
     *                  {
     *                      "fieldName": "is_searchable",
     *                      "fieldVisibleName": "is_searchable",
     *                      "isCreatable": true,
     *                      "isEditable": true,
     *                      "type": "boolean",
     *                      "validation": { "isRequired": true }
     *                  },
     *                  {
     *                      "fieldName": "rate",
     *                      "fieldVisibleName": "rate",
     *                      "isCreatable": true,
     *                      "isEditable": true,
     *                      "type": "decimal",
     *                      "validation": { "isRequired": false, "precision": 5, "scale": 2}
     *                  }
     *              ]
     *          }
     *      }
     *  ]
     *
     * @api
     *
     * @throw \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityFilePathNotDefinedException
     *
     * @return string
     */
    public function getInstallerConfigurationDataFilePath(): string
    {
        throw new DynamicEntityFilePathNotDefinedException('File path should be defined.');
    }

    /**
     * Specification:
     * - Returns a list of tables that should not be used for dynamic entity configuration.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDisallowedTables(): array
    {
        return [];
    }
}
