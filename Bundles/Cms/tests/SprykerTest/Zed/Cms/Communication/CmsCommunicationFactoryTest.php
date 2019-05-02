<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Communication;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Communication
 * @group CmsCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class CmsCommunicationFactoryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Cms\CmsCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetTemplateRealPathsReturnsOnlyDefaultThemeTemplatePaths(): void
    {
        $templatePaths = $this->getFactory()->getTemplateRealPaths('/foo/bar');

        $this->assertCount(2, $templatePaths, 'Expected only default template paths.');
    }

    /**
     * @return void
     */
    public function testGetTemplateRealPathsReturnsDefaultAndCustomThemeTemplatePaths(): void
    {
        $this->tester->mockConfigMethod('getThemeNames', ['custom', 'default']);
        $templatePaths = $this->getFactory()->getTemplateRealPaths('/foo/bar');

        $this->assertCount(4, $templatePaths, 'Expected custom and default template paths.');
    }

    /**
     * @return \Spryker\Zed\Cms\Communication\CmsCommunicationFactory
     */
    protected function getFactory()
    {
        return $this->tester->getFactory();
    }
}
