<?php

namespace Functional\SprykerFeature\Zed\GlossaryQueue\Communication\Plugin;

use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\GlossaryQueue\Dependency\Facade\GlossaryQueueToGlossaryInterface;

class MockGlossaryFacade extends GlossaryFacade implements GlossaryQueueToGlossaryInterface
{

}
