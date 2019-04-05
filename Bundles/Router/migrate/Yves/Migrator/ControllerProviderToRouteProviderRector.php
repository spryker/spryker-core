<?php declare(strict_types = 1);

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Router\Migrate\Yves\Migrator;

use Exception;
use Nette\Utils\Strings;
use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class ControllerProviderToRouteProviderRector extends AbstractRector
{
    /**
     * @var string[]
     */
    protected $methodNamesToRefactor = [
        'createController',
        'createGetController',
        'createPostController',
    ];

    /**
     * @var array|null
     */
    protected $constantKeyValueMap;

    /**
     * @var \PhpParser\BuilderFactory
     */
    protected $builderFactory;

    /**
     * @var \Rector\PhpParser\Node\BetterNodeFinder
     */
    protected $betterNodeFinder;

    /**
     * @param \Rector\PhpParser\Node\BetterNodeFinder $betterNodeFinder
     */
    public function __construct(BetterNodeFinder $betterNodeFinder)
    {
        $this->betterNodeFinder = $betterNodeFinder;
        $this->builderFactory = new BuilderFactory();
    }

    /**
     * @return \Rector\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrates ControllerProvider to RouteProviderPlugins', [
            new CodeSample(
                'class ModuleControllerProvider extends AbstractYvesControllerProvider',
                'class ModuleControllerProvider extends AbstractRouteProviderPlugin'
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_|\PhpParser\Node $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!Strings::endsWith((string)$node->name, 'ControllerProvider')) {
            return null;
        }

        $extends = new FullyQualified(AbstractRouteProviderPlugin::class);
        $node->extends = $extends;

        $newClassName = new Identifier(str_replace('ControllerProvider', 'RouteProviderPlugin', (string)$node->name));
        $node->name = $newClassName;

        foreach ($node->stmts as $statement) {
            $this->refactorStatement($statement);
        }

        $this->constantKeyValueMap = null;

        return $node;
    }

    /**
     * @param \PhpParser\Node\Stmt $statement
     *
     * @return void
     */
    protected function refactorStatement(Stmt $statement): void
    {
        if ($statement instanceof ClassMethod) {
            if ((string)$statement->name === 'defineControllers') {
                $this->refactorDefineControllerMethodToAddRouteMethod($statement);
            }

            if (preg_match('/^add(.*?)Route$/', (string)$statement->name)) {
                $this->refactorAddXRouteMethod($statement);
            }
        }
    }

    /**
     * This will refactor `defineController(Application $app)` method to `addRoutes(RouteCollection $routeCollection): RouteCollection`.
     *
     * @param \PhpParser\Node\Stmt\ClassMethod $method
     *
     * @return void
     */
    protected function refactorDefineControllerMethodToAddRouteMethod(ClassMethod $method): void
    {
        $method->stmts = $this->refactorMethodBody($method->stmts);
        $method->flags = Class_::MODIFIER_PUBLIC;
        $method->name = 'addRoutes';
        $method->params = [
            $this->builderFactory->param('routeCollection')->setType('\\' . RouteCollection::class)->getNode(),
        ];
        $method->returnType = new Identifier('\\' . RouteCollection::class);

        $docComments[] = sprintf(' * @param %s $routeCollections', '\\' . RouteCollection::class);
        $docComments[] = ' *';
        $docComments[] = sprintf(' * @return %s', '\\' . RouteCollection::class);

        $docComment = new Doc(sprintf('/**%s%s%s */', PHP_EOL, implode("\n", $docComments), PHP_EOL));
        $method->setDocComment($docComment);
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $method
     *
     * @return void
     */
    protected function refactorAddXRouteMethod(ClassMethod $method): void
    {
        $method->stmts = $this->refactorMethodBody($method->stmts);
        $method->params = [
            $this->builderFactory->param('routeCollection')->setType('\\' . RouteCollection::class)->getNode(),
        ];
        $method->returnType = new Identifier('\\' . RouteCollection::class);

        $docComments = $this->getUsesAnnotations((string)$method->getDocComment());

        if (count($docComments) > 0) {
            $docComments[] = ' *';
        }

        $docComments[] = sprintf(' * @param %s $routeCollection', '\\' . RouteCollection::class);
        $docComments[] = ' *';
        $docComments[] = sprintf(' * @return %s', '\\' . RouteCollection::class);

        $docComment = new Doc(sprintf('/**%s%s%s */', PHP_EOL, implode("\n", $docComments), PHP_EOL));
        $method->setDocComment($docComment);
    }

    /**
     * @param string $docComment
     *
     * @return array
     */
    protected function getUsesAnnotations(string $docComment): array
    {
        $usesAnnotations = [];

        if (preg_match_all('/@uses\s(.*)/', $docComment, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $usesAnnotations[] = sprintf(' * %s', $match[0]);
            }
        }

        return $usesAnnotations;
    }

    /**
     * This will refactor all `createController()` methods to `addRoute` methods.
     *
     * @param array $statements
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function refactorMethodBody(array $statements): array
    {
        $refactoredStatements = [];

        foreach ($statements as $statement) {
            if ($statement instanceof Nop) {
                throw new Exception(sprintf('Found "%s" statement, this is usually found when you have to many methods chained. You can fix this by manually splitting the chain into smaller parts (max 10 calls per chain)', Nop::class));
            }

            if ($statement instanceof Return_) {
                continue;
            }

            if ($statement->expr instanceof MethodCall) {
                $nestedChainMethodCalls = $this->getNestedChainMethodCalls($statement->expr);

                $createControllerMethod = $this->findCreateXControllerMethod($nestedChainMethodCalls);

                if ($createControllerMethod) {
                    $refactoredCreateMethodStatements = $this->refactorCreateControllerMethod($nestedChainMethodCalls);
                    $refactoredStatements = array_merge($refactoredStatements, $refactoredCreateMethodStatements);

                    continue;
                }

                if ($this->containsAddXRouteMethod($statement)) {
                    foreach ($nestedChainMethodCalls as $nestedChainMethodCall) {
                        $addRouteMethodCall = new MethodCall(
                            new Variable('this'),
                            $nestedChainMethodCall->name->name,
                            $this->builderFactory->args([
                                new Variable('routeCollection'),
                            ])
                        );

                        $assignRouteExpression = new Assign(new Variable('routeCollection'), $addRouteMethodCall);
                        $refactoredStatements[] = new Expression($assignRouteExpression);
                    }

                    continue;
                }
            }

            // in case we have an expression which we did not handled we need to add it to not apparently remove something which is needed by the project
            $refactoredStatements[] = $statement;
        }

        $refactoredStatements[] = new Return_(new Variable('routeCollection'));

        return $refactoredStatements;
    }

    /**
     * @param array $nestedChainMethodCalls
     *
     * @return array
     */
    protected function refactorCreateControllerMethod(array $nestedChainMethodCalls): array
    {
        $newNestedChainMethodCalls = [];
        $routeInformation = $this->extractRouteInformation(array_shift($nestedChainMethodCalls));

        // First assert is the one which asserts with allowedLocalePattern, we don't need this node anymore
        // throw away first assert
        $currentMethod = current($nestedChainMethodCalls);

        if ($currentMethod) {
            if ($currentMethod->name->name === 'assert') {
                array_shift($nestedChainMethodCalls);
            }

            foreach ($nestedChainMethodCalls as $nestedChainMethodCall) {
                $methodName = $nestedChainMethodCall->name->name;

                if ($methodName === 'method') {
                    $newNestedChainMethodCalls[] = $nestedChainMethodCall;

                    continue;
                }

                $methodArguments = $nestedChainMethodCall->args;

                $argumentKey = $this->getArgumentKey($methodArguments[0]);
                $argumentValueType = $methodArguments[1]->value;

                if ($methodName === 'value' && isset($routeInformation['placeholder'][$argumentKey]) && $argumentValueType instanceof String_) {
                    $routeInformation['placeholder'][$argumentKey]['value'] = $argumentValueType->value;

                    continue;
                }

                $newNestedChainMethodCalls[] = $nestedChainMethodCall;
            }
        }

        $buildRouteMethodCall = new MethodCall(
            new Variable('this'),
            new Identifier('buildRoute'),
            $this->builderFactory->args([
                $this->buildUrl($routeInformation),
                $routeInformation['moduleName'],
                $routeInformation['controllerName'],
                $routeInformation['actionName'],
            ])
        );

        // What if this is not a const but a string?
        $routeNameConst = $this->builderFactory->classConstFetch('static', $routeInformation['routeName']);

        $expressions = [
            new Expression(new Assign(new Variable('route'), $buildRouteMethodCall)),
        ];

        foreach ($newNestedChainMethodCalls as $newNestedChainMethodCall) {
            $expressions[] = new Expression(
                new Assign(
                    new Variable('route'),
                    $this->builderFactory->methodCall(
                        new Variable('route'),
                        $newNestedChainMethodCall->name,
                        $newNestedChainMethodCall->args
                    )
                )
            );
        }

        $expressions[] = new Expression($this->builderFactory->methodCall(new Variable('routeCollection'), 'add', [$routeNameConst, new Variable('route')]));

        return $expressions;
    }

    /**
     * @param \PhpParser\Node\Arg $argNode
     *
     * @throws \Rector\Exception\ShouldNotHappenException
     *
     * @return string
     */
    protected function getArgumentKey(Arg $argNode): string
    {
        if ($argNode->value instanceof String_) {
            return $argNode->value->value;
        }

        if ($argNode->value instanceof ClassConstFetch) {
            return $this->getConstantValueByKey($argNode->getAttribute('classNode'), $argNode->value->name->name);
        }

        throw new ShouldNotHappenException('Could not find key for argument.');
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     *
     * @return array
     */
    protected function extractRouteInformation(MethodCall $methodCall): array
    {
        $arguments = $methodCall->args;

        $actionName = 'indexAction';

        if (isset($arguments[4])) {
            $actionName = $arguments[4]->value->value;

            if (strpos('Action', $actionName) === false) {
                $actionName = sprintf('%sAction', $actionName);
            }
        }

        $url = $this->getUrl($arguments[0]);

        $urlFragments = explode('/', trim($url, '/'));
        $parsedUrlFragments = [];

        foreach ($urlFragments as $urlFragment) {
            $urlFragment = [
                'origin' => $urlFragment,
                'placeholder' => [],
            ];

            if (preg_match_all('/{(.*?)}/', $urlFragment['origin'], $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $urlFragment['placeholder'][$match[1]] = [
                        'key' => $match[1],
                        'value' => null,
                    ];
                }
            }
            $parsedUrlFragments[] = $urlFragment;
        }

        $routeInformation = [
            'urlFragments' => $parsedUrlFragments,
            'placeholder' => $this->extractPlaceholderFromUrl($url),
            'routeName' => $arguments[1]->value->name,
            'moduleName' => $arguments[2]->value->value,
            'controllerName' => $arguments[3]->value->value,
            'actionName' => $actionName,
        ];

        return $routeInformation;
    }

    /**
     * @param Node\Arg $argument
     *
     * @return string
     */
    protected function getUrl(Arg $argument): string
    {
        if ($argument->value instanceof ClassConstFetch) {
            return $this->getConstantValueByKey($argument->value->getAttribute('classNode'), (string)$argument->value->name);
        }

        return $argument->value->value;
    }

    /**
     * @param array $routeInformation
     *
     * @return string
     */
    protected function buildUrl(array $routeInformation): string
    {
        $url = '';

        foreach ($routeInformation['urlFragments'] as $urlFragment) {
            if (count($urlFragment['placeholder']) === 0) {
                $url .= sprintf('/%s', $urlFragment['origin']);

                continue;
            }

            $placeholder = '';

            foreach ($urlFragment['placeholder'] as $key => $data) {
                if (isset($routeInformation['placeholder'][$key]['value'])) {
                    $placeholder .= $routeInformation['placeholder'][$key]['value'];

                    continue;
                }

                if (!isset($routeInformation['placeholder'][$key]['value'])) {
                    $placeholder .= sprintf('{%s}', $key);

                    continue;
                }
            }
            $url .= sprintf('/%s', $placeholder);
        }

        return $url;
    }

    /**
     * @param string $url
     *
     * @return array
     */
    protected function extractPlaceholderFromUrl(string $url): array
    {
        $placeholders = [];

        if (preg_match_all('/{(.*?)}/', $url, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $placeholders[$match[1]] = [
                    'name' => $match[1],
                ];
            }
        }

        return $placeholders;
    }

    /**
     * @param array $expressions
     *
     * @return \PhpParser\Node\Expr\MethodCall|null
     */
    protected function findCreateXControllerMethod(array $expressions): ?MethodCall
    {
        foreach ($expressions as $expression) {
            if (in_array((string)$expression->name->name, $this->methodNamesToRefactor)) {
                return $expression;
            }
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Stmt\Expression $expression
     *
     * @return bool
     */
    protected function containsAddXRouteMethod(Expression $expression): bool
    {
        $lastMethodCall = current($this->getNestedChainMethodCalls($expression->expr));

        if ($lastMethodCall && preg_match('/add(.*?)Route/', (string)$lastMethodCall->name)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     *
     * @return \PhpParser\Node\Expr\MethodCall[]
     */
    protected function getNestedChainMethodCalls(MethodCall $methodCall): array
    {
        $methodCalls = [];

        do {
            $methodCalls[] = $methodCall;
            $methodCall = $methodCall->var;
        } while ($methodCall instanceof MethodCall);

        return array_reverse($methodCalls);
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $classNode
     * @param string $constantKey
     *
     * @throws \Rector\Exception\ShouldNotHappenException
     *
     * @return string
     */
    protected function getConstantValueByKey(Class_ $classNode, string $constantKey): string
    {
        if (!$this->hasConstantKey($classNode, $constantKey)) {
            throw new ShouldNotHappenException(sprintf('Could not find constant by key "%s"', $constantKey));
        }

        return $this->getConstantKeyValueMap($classNode)[$constantKey];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $classNode
     * @param string $constantKey
     *
     * @return bool
     */
    protected function hasConstantKey(Class_ $classNode, string $constantKey): bool
    {
        return isset($this->getConstantKeyValueMap($classNode)[$constantKey]);
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $classNode
     * @param string $constantValue
     *
     * @throws \Rector\Exception\ShouldNotHappenException
     *
     * @return string
     */
    protected function getConstantKeyByValue(Class_ $classNode, string $constantValue): string
    {
        if (!$this->hasConstantValue($classNode, $constantValue)) {
            throw new ShouldNotHappenException(sprintf('Could not find constant key by constant vallue "%s"', $constantValue));
        }

        return $this->getConstantKeyValueMap($classNode)[$constantValue];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $classNode
     * @param string $constantValue
     *
     * @return bool
     */
    protected function hasConstantValue(Class_ $classNode, string $constantValue): bool
    {
        return isset(array_flip($this->getConstantKeyValueMap($classNode))[$constantValue]);
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_ $classNode
     *
     * @return array
     */
    protected function getConstantKeyValueMap(Class_ $classNode): array
    {
        if ($this->constantKeyValueMap === null) {
            $constants = $this->betterNodeFinder->findInstanceOf($classNode->stmts, Const_::class);

            foreach ($constants as $constant) {
                $this->constantKeyValueMap[(string)$constant->name] = $constant->value->value;
            }
        }

        return $this->constantKeyValueMap;
    }
}
