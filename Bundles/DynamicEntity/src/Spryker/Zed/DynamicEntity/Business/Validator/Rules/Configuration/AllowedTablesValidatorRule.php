<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;

class AllowedTablesValidatorRule implements ValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Table name is not allowed for dynamic entity. Table: %s';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const TABLE_ALIAS = 'table_alias';

    /**
     * @var \Spryker\Zed\DynamicEntity\DynamicEntityConfig
     */
    protected DynamicEntityConfig $dynamicEntityConfig;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReaderInterface
     */
    protected DisallowedTablesReaderInterface $disallowedTablesReader;

    /**
     * @param \Spryker\Zed\DynamicEntity\DynamicEntityConfig $dynamicEntityConfig
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DisallowedTablesReaderInterface $disallowedTablesReader
     */
    public function __construct(DynamicEntityConfig $dynamicEntityConfig, DisallowedTablesReaderInterface $disallowedTablesReader)
    {
        $this->dynamicEntityConfig = $dynamicEntityConfig;
        $this->disallowedTablesReader = $disallowedTablesReader;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $dynamicEntityConfigurationTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            if (!in_array($dynamicEntityConfigurationTransfer->getTableName(), $this->disallowedTablesReader->getDisallowedTables())) {
                continue;
            }

            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE, $dynamicEntityConfigurationTransfer->getTableName()))
                    ->setParameters([static::TYPE => static::TABLE_ALIAS])
                    ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableName()),
            );
        }

        return $errorCollectionTransfer;
    }
}
