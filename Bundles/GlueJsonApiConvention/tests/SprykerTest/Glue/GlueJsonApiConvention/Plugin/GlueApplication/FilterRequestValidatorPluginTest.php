<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueFilterTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\FilterRequestValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Plugin
 * @group GlueApplication
 * @group FilterRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class FilterRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_VALUE = 'TEST_VALUE';

    /**
     * @return void
     */
    public function testFilterRequestValidatorPluginContainsValidRequestFilterData(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $filterRequestValidatorPlugin = $this->createFilterRequestValidatorPlugin();
        $glueRequestValidationTransfer = $filterRequestValidatorPlugin->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testFilterRequestValidatorPluginNotContainsRequestFilterData(): void
    {
        //Act
        $glueRequestTransfer = $this->createGlueRequestTransfer();

        //Act
        $filterRequestValidatorPlugin = $this->createFilterRequestValidatorPlugin();
        $glueRequestValidationTransfer = $filterRequestValidatorPlugin->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testFilterRequestValidatorPluginContainsInvalidRequestFilterData(): void
    {
        //Act
        $glueRequestTransfer = $this->createGlueRequestTransfer()->addFilter(new GlueFilterTransfer());

        //Act
        $filterRequestValidatorPlugin = $this->createFilterRequestValidatorPlugin();
        $glueRequestValidationTransfer = $filterRequestValidatorPlugin->validate($glueRequestTransfer);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface
     */
    protected function createFilterRequestValidatorPlugin(): RequestValidatorPluginInterface
    {
        return new FilterRequestValidatorPlugin();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function createGlueRequestTransfer(): GlueRequestTransfer
    {
        return new GlueRequestTransfer();
    }
}
