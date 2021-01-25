<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\CmsSlotBlockStorage;

use Codeception\TestCase\Test;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group CmsSlotBlockStorage
 * @group CmsSlotBlockStorageServiceTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceTester $tester
 */
class CmsSlotBlockStorageServiceTest extends Test
{
    protected const KEY_SLOT = 'slt-1';
    protected const PATH_TEMPLATE = '@Home/index/home.tpl';
    protected const GENERATED_SLOT_TEMPLATE_KEY = '@Home/index/home.tpl:slt-1';

    /**
     * @return void
     */
    public function testGenerateSlotTemplateKey(): void
    {
        $generatedSlotTemplateKey = $this->tester->getCmsSlotBlockStorageService()
            ->generateSlotTemplateKey(static::PATH_TEMPLATE, static::KEY_SLOT);

        $this->assertSame(static::GENERATED_SLOT_TEMPLATE_KEY, $generatedSlotTemplateKey);
    }
}
