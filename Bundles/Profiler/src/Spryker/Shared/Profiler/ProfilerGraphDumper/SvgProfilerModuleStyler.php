<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\ProfilerGraphDumper;

use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface;

class SvgProfilerModuleStyler implements ProfilerGraphNodeStylerInterface
{
    /**
     * @param \Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNodeInterface $node
     *
     * @return string
     */
    public function apply(ProfilerGraphNodeInterface $node): string
    {
        $matches = [];
        preg_match('/\\w+\\\\(Zed|Client|Glue|Service|Yves|Shared)\\\\(?<module>\\w+)\\\\/', $node->getName(), $matches);
        $color = isset($matches['module']) ? substr(md5($matches['module']), 0, 6) : '0000ff';

        return sprintf('"%s" [shape=box,style=filled,color="#%s80"];', $node->getName(), $color);
    }
}
