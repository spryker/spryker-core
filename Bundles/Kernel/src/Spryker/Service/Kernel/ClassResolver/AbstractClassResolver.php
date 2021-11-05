<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver as SharedAbstractClassResolver;

abstract class AbstractClassResolver extends SharedAbstractClassResolver
{
    /**
     * @var string
     */
    public const KEY_NAMESPACE = '%namespace%';

    /**
     * @var string
     */
    public const KEY_APPLICATION = '%application%';

    /**
     * @var string
     */
    public const KEY_BUNDLE = '%bundle%';

    /**
     * @var string
     */
    public const KEY_CODE_BUCKET = '%codeBucket%';

    /**
     * @param string $namespace
     * @param string|null $codeBucket
     *
     * @return string
     */
    protected function buildClassName($namespace, $codeBucket = null)
    {
        $searchAndReplace = [
            static::KEY_NAMESPACE => $namespace,
            static::KEY_APPLICATION => 'Service',
            static::KEY_BUNDLE => $this->getClassInfo()->getModule(),
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        return str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern(),
        );
    }
}
