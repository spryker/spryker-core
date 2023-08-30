<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Validator\Rules\Configuration;

use Codeception\Test\Unit;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration\ResourceNameValidatorRule;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Validator
 * @group Rules
 * @group Configuration
 * @group ResourceNameValidatorRuleTest
 * Add your own group annotations below this line
 */
class ResourceNameValidatorRuleTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DynamicEntity\DynamicEntityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateWillReturnCollectionWithError(): void
    {
        // Arrange
        $validatorRule = new ResourceNameValidatorRule();
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer(null, '$abc');

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(1, $errorCollectionTransfer->getErrors());
        $this->assertSame('Resource name `$abc` is not valid. Allowed characters: a-z, A-Z, 0-9, _ and - ', $errorCollectionTransfer->getErrors()[0]->getMessage());
        $this->assertSame('spy_test_table', $errorCollectionTransfer->getErrors()[0]->getEntityIdentifier());
    }

    /**
     * @dataProvider tableAliasesDataProvider
     *
     * @param string $tableAlias
     *
     * @return void
     */
    public function testValidateWillReturnCollectionWithoutError(string $tableAlias): void
    {
        // Arrange
        $validatorRule = new ResourceNameValidatorRule();
        $dynamicEntityConfigurationCollectionRequestTransfer = $this->tester->createDynamicEntityConfigurationCollectionRequestTransfer(null, $tableAlias);

        // Act
        $errorCollectionTransfer = $validatorRule->validate($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations());

        // Assert
        $this->assertCount(0, $errorCollectionTransfer->getErrors());
    }

    /**
     * @return array[]
     */
    protected function tableAliasesDataProvider(): array
    {
        return [
            ['some_123_resource_name'],
            ['some-123-resource-name'],
            ['some-123-resource_name'],
        ];
    }
}
