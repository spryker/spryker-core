<?php
/**
 * Created by PhpStorm.
 * User: dsavin
 * Date: 02.07.15
 * Time: 19:03
 */

namespace SprykerFeature\Zed\UiExample\Communication\Table;


use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\Config\Definition\Exception\Exception;

class   BaseTable {

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @var array
     */
    private $data;

    public function __construct(){
        /**
         * @var Locator
         */
        $this->locator = Locator::getInstance();

    }

    public function loadData(array $data){
        $this->data = $data;
    }

    public function render(){

        return $this->getTwig()->render('index.twig', $this->data);
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return \Twig_Environment
     * @throws \LogicException
     */
    private function getTwig()
    {
        /** @var \Twig_Environment $twig */
        $twig = $this
            ->locator
            ->application()
            ->pluginPimple()
            ->getApplication()['twig'];

        $twig
            ->getLoader()
            ->addLoader(
                new \Twig_Loader_Filesystem(
                    __DIR__ . '/../../Presentation/Table/'
                )
            );

        if ($twig === null) {
            throw new \LogicException('Twig environment not set up.');
        }

        return $twig;
    }
}
