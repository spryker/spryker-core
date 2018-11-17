<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Form\Transformer;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface;
use Symfony\Component\Form\DataTransformerInterface;

class RuleQuerySetTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(ProductRelationToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * This method is called on two occasions inside a form field:
     *
     * 1. When the form field is initialized with the data attached from the datasource (object or array).
     * 2. When data from a request is submitted using {@link Form::submit()} to transform the new input data
     *    back into the renderable format. For example if you have a date field and submit '2009-10-10'
     *    you might accept this value because its easily parsed, but the transformer still writes back
     *    "2009/10/10" onto the form field (for further displaying or other purposes).
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that value transformers must be chainable. If the transform() method
     * of the first value transformer outputs NULL, the second value transformer
     * must be able to process that value.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer|null $ruleQuerySetTransfer The value in the original representation
     *
     * @return mixed|null The value in the transformed representation
     */
    public function transform($ruleQuerySetTransfer)
    {
        if (!$ruleQuerySetTransfer) {
            return null;
        }

        if (count($ruleQuerySetTransfer->getRules()) === 0) {
            return null;
        }

        return $this->utilEncodingService->encodeJson($ruleQuerySetTransfer->toArray());
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format for your data processing/model layer.
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as NULL). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
        $querySetData = $this->utilEncodingService->decodeJson($value, true);

        $querySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $querySetTransfer->fromArray($querySetData, true);

        return $querySetTransfer;
    }
}
