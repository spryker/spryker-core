<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Communication\Command\Builder;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Communication\Command\Builder\PropelCommandBuilder;
use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Communication
 * @group Command
 * @group Builder
 * @group PropelCommandBuilderTest
 * Add your own group annotations below this line
 */
class PropelCommandBuilderTest extends Unit
{
    /**
     * @var \Spryker\Zed\Propel\Communication\Command\Builder\PropelCommandBuilderInterface
     */
    protected $propelCommandBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->propelCommandBuilder = new PropelCommandBuilder(
            $this->getPropelCommandConfiguratorMock()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface
     */
    protected function getPropelCommandConfiguratorMock()
    {
        return $this->getMockBuilder(PropelCommandConfiguratorInterface::class)->getMock();
    }
}
