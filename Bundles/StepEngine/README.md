# StepEngine Module

StepEngine implements an engine for building a process of steps (a software wizard) e.g. a multistep checkout. It defines the main skeleton of every step and the logic to run it. Every step has four main functionalities: precondition, required input, execute, and post-conditions. It also defines the process that connects the steps together and manages the movement between them. The steps are built as a linked list where every step is a node with pointers to the next and the previous step. The StepEngine is used for example by the Checkout module to build and manage the steps.

## Installation

```
composer require spryker/step-engine
```

## Documentation

[Module Documentation](http://academy.spryker.com/developing_with_spryker/module_guide/engines/step_engine/step_engine.html)
