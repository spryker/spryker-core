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
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer|null $value
     *
     * @return array|null
     */
    public function transform($value): ?array
    {
        return $value ? $value->toArray(true, true) : null;
    }

    /**
     * @param array|null $value
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer|null
     */
    public function reverseTransform($value): ?CmsSlotBlockTransfer
    {
        if (!$value) {
            return null;
        }

        $conditions = $value[CmsSlotBlockTransfer::CONDITIONS];
        unset($value[CmsSlotBlockTransfer::CONDITIONS]);

        $cmsSlotBlockTransfer = (new CmsSlotBlockTransfer())->fromArray($value, true);
        foreach ($conditions as $conditionKey => $condition) {
            $cmsSlotBlockTransfer->addCondition(
                $conditionKey,
                (new CmsSlotBlockConditionTransfer())->fromArray($condition, true),
            );
        }

        return $cmsSlotBlockTransfer;
    }
}
