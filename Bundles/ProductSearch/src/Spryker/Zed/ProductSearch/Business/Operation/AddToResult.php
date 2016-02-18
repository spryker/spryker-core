<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Operation;

class AddToResult implements OperationInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'AddToResult';
    }

    /**
     * @param array $sourceDocument
     * @param array $targetDocument
     * @param mixed $sourceField
     * @param string $targetField
     *
     * @return array
     */
    public function enrichDocument(array $sourceDocument, array $targetDocument, $sourceField, $targetField)
    {
        if (isset($sourceDocument[$sourceField]) && !empty($sourceDocument[$sourceField])) {
            $targetDocument[$targetField][$sourceField] = $sourceDocument[$sourceField];
        }

        return $targetDocument;
    }

}
