<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Propel\Runtime\Map\DatabaseMap;
use Spryker\Zed\DynamicEntityGui\Communication\Form\CreateDynamicDataConfigurationForm;
use Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface;

class CreateDynamicDataConfigurationFormDataProvider
{
    /**
     * @var \Propel\Runtime\Map\DatabaseMap
     */
    protected DatabaseMap $databaseMap;

    /**
     * @var \Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface
     */
    protected TableValidatorInterface $tableValidator;

    /**
     * @param \Propel\Runtime\Map\DatabaseMap $databaseMap
     * @param \Spryker\Zed\DynamicEntityGui\Communication\Validator\TableValidatorInterface $tableValidator
     */
    public function __construct(
        DatabaseMap $databaseMap,
        TableValidatorInterface $tableValidator
    ) {
        $this->databaseMap = $databaseMap;
        $this->tableValidator = $tableValidator;
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>>
     */
    public function getOptions(): array
    {
        return [
            CreateDynamicDataConfigurationForm::OPTION_TABLE_NAME_CHOICES => $this->getTableNameChoices(),
        ];
    }

    /**
     * @return array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    public function getTableNameChoices(): array
    {
        $data = [];
        $databaseTables = array_keys($this->databaseMap->getTables());

        foreach ($databaseTables as $table) {
            if ($this->tableValidator->validateIsTableDisallowed($table) === true) {
                continue;
            }

            if ($this->tableValidator->validateIsTableConfigured($table) === true) {
                continue;
            }

            $data[] = (new DynamicEntityConfigurationTransfer())
                ->setTableName($table);
        }

        return $data;
    }
}
