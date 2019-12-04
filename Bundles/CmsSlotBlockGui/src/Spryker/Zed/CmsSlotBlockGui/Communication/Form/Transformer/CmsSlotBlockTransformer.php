<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\Transformer;

use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class CmsSlotBlockTransformer implements DataTransformerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer|null $cmsSlotBlockTransfer
     *
     * @return array|null
     */
    public function transform($cmsSlotBlockTransfer): ?array
    {
        return $cmsSlotBlockTransfer ? $cmsSlotBlockTransfer->toArray(true, true) : null;
    }

    /**
     * @param array|null $cmsSlotBlock
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer|null
     */
    public function reverseTransform($cmsSlotBlock): ?CmsSlotBlockTransfer
    {
        if (!$cmsSlotBlock) {
            return null;
        }

        $conditions = $cmsSlotBlock[CmsSlotBlockTransfer::CONDITIONS];
        unset($cmsSlotBlock[CmsSlotBlockTransfer::CONDITIONS]);

        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->fromArray($cmsSlotBlock, true);
        foreach ($conditions as $conditionKey => $condition) {
            $cmsSlotBlockTransfer->addCondition(
                $conditionKey,
                (new CmsSlotBlockConditionTransfer())->fromArray($condition, true)
            );
        }

        return $cmsSlotBlockTransfer;
    }
}
