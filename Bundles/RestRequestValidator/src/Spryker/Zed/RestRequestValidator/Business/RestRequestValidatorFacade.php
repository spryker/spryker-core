<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory getFactory()
 */
class RestRequestValidatorFacade extends AbstractFacade implements RestRequestValidatorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link buildValidationCacheForCodeBucket()} instead.
     *
     * @return void
     */
    public function buildValidationCache(): void
    {
        $this->removeValidationCache();
        $this->getFactory()->createRestRequestValidatorCacheBuilder()->build();
    }

    /**
     * @inheritDoc
     *
     * @api
     *
     * @param string $codeBucket
     *
     * @return void
     */
    public function buildValidationCacheForCodeBucket(string $codeBucket): void
    {
        $this->removeValidationCacheForCodeBucket($codeBucket);
        $this->getFactory()
            ->createRestRequestValidatorCodeBucketCacheBuilder()
            ->buildCacheForCodeBucket($codeBucket);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link removeValidationCacheForCodeBucket()} instead.
     *
     * @return void
     */
    public function removeValidationCache(): void
    {
        $this->getFactory()->createRestRequestValidatorCacheRemover()->remove();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $codeBucket
     *
     * @return void
     */
    public function removeValidationCacheForCodeBucket(string $codeBucket): void
    {
        $this->getFactory()
            ->createRestRequestValidatorCodeBucketCacheRemover()
            ->remove($codeBucket);
    }
}
