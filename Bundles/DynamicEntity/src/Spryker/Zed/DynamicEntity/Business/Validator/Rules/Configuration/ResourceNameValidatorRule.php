<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;

class ResourceNameValidatorRule implements ValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Resource name `%s` is not valid. Allowed characters: a-z, A-Z, 0-9, _ and - ';

    /**
     * @var string
     */
    protected const RESOURCE_NAME_REGEX = '/^[a-zA-Z0-9_\-]+$/';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const TABLE_ALIAS = 'table_alias';

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $dynamicEntityConfigurationTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer */
        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            if ($this->isResourceNameValid($dynamicEntityConfigurationTransfer->getTableAliasOrFail())) {
                continue;
            }

            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE, $dynamicEntityConfigurationTransfer->getTableAlias()))
                    ->setParameters([static::TYPE => static::TABLE_ALIAS])
                    ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableName()),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param string $resourceName
     *
     * @return bool
     */
    protected function isResourceNameValid(string $resourceName): bool
    {
        return (bool)preg_match(static::RESOURCE_NAME_REGEX, $resourceName);
    }
}
