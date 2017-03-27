<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Mapper;

class FieldMapper implements FieldMapperInterface
{

    /**
     * @param array $data
     * @param array $allowedFields
     *
     * @return array
     */
    public function mapFields(array $data, array $allowedFields = [])
    {
        if (empty($allowedFields)) {
            return [];
        }

        return array_intersect_key(
            $data,
            array_flip($allowedFields)
        );
    }

}
