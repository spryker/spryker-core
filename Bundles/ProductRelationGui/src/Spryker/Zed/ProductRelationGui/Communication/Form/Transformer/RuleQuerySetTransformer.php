<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Form\Transformer;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

class RuleQuerySetTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Service\ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductRelationGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer|null $value
     *
     * @return string|null
     */
    public function transform($value)
    {
        if (!$value || count($value->getRules()) === 0) {
            return null;
        }

        return $this->utilEncodingService->encodeJson($value->toArray());
    }

    /**
     * @param string|null $value
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function reverseTransform($value)
    {
        $querySetData = $this->utilEncodingService->decodeJson($value, true);

        $querySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $querySetTransfer->fromArray($querySetData, true);

        return $querySetTransfer;
    }
}
