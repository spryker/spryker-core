<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model;

interface TemplatePathMapBuilderInterface
{
    /**
     * Returns an array where the key is the template name
     * and the value is the path to this template.
     *
     * @return array
     */
    public function build();
}
