<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Merger;

use function array_merge_recursive;

class RestRequestValidatorMerger implements RestRequestValidatorMergerInterface
{
    /**
     * @param array $validatorSchema
     *
     * @return array
     */
    public function merge(array $validatorSchema): array
    {
        $validatorSchemaMerged = [];
        foreach ($validatorSchema as $moduleName => $validationSchemas) {
            if (count($validationSchemas) === 1) {
                $validatorSchemaMerged[$moduleName] = reset($validationSchemas);
            } else {
                $validatorSchemaMerged[$moduleName] = [];
                foreach ($validationSchemas as $validationSchema) {
                    $validatorSchemaMerged[$moduleName]
                        = array_merge_recursive($validatorSchemaMerged[$moduleName], $validationSchema);
                }
            }
        }

        return $validatorSchemaMerged;
    }
}
