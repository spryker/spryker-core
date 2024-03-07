<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\DynamicEntity\Dependency\Service\DynamicEntityToUtilEncodingServiceInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class InFilterStrategy implements FilterStrategyInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Dependency\Service\DynamicEntityToUtilEncodingServiceInterface
     */
    protected DynamicEntityToUtilEncodingServiceInterface $serviceUtilEncoding;

    /**
     * @param \Spryker\Zed\DynamicEntity\Dependency\Service\DynamicEntityToUtilEncodingServiceInterface $serviceUtilEncoding
     */
    public function __construct(DynamicEntityToUtilEncodingServiceInterface $serviceUtilEncoding)
    {
        $this->serviceUtilEncoding = $serviceUtilEncoding;
    }

    /**
     * @param string|null $fieldValue
     *
     * @return bool
     */
    public function isApplicable(?string $fieldValue): bool
    {
        if ($fieldValue === null) {
            return false;
        }

        $decodedJson = $this->serviceUtilEncoding->decodeJson($fieldValue, true);

        return is_array($decodedJson)
            && count($decodedJson) === 1
            && (
                array_key_exists(trim(Criteria::IN), $decodedJson)
                || array_key_exists(trim(strtolower(Criteria::IN)), $decodedJson)
            );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $fieldConditionName
     * @param string|null $fieldValue
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyConditionToQuery(
        ModelCriteria $query,
        string $fieldConditionName,
        ?string $fieldValue
    ): ModelCriteria {
        if ($fieldValue === null) {
            return $query;
        }

        $decodedJson = $this->serviceUtilEncoding->decodeJson($fieldValue, true);

        if (!is_array($decodedJson)) {
            return $query;
        }

        return $query->filterBy($fieldConditionName, array_shift($decodedJson), Criteria::IN);
    }
}
