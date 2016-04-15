<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Operation;

class DefaultOperation implements OperationInterface
{

    /**
     * @param array $sourceDocument
     * @param array $targetDocument
     * @param string $sourceField
     * @param string $targetField
     *
     * @return array
     */
    public function enrichDocument(array $sourceDocument, array $targetDocument, $sourceField, $targetField)
    {
        return $targetDocument;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Default';
    }

}
