<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Mapper;

interface FieldMapperInterface
{

    /**
     * @param array $data
     * @param array $allowedFields
     *
     * @return array
     */
    public function mapFields(array $data, array $allowedFields = []);

}
