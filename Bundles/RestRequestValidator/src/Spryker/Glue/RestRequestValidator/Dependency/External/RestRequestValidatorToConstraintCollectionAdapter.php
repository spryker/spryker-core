<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Dependency\External;

use Symfony\Component\Validator\Constraints\Collection;

class RestRequestValidatorToConstraintCollectionAdapter implements RestRequestValidatorToConstraintCollectionAdapterInterface
{
    /**
     * @param array|null $options
     *
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    public function createCollection(?array $options = null): Collection
    {
        return new Collection($options);
    }
}
