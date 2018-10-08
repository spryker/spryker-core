{
  "name": "{namespaceDashed}/{moduleDashed}",
  "type": "library",
  "description": "{module} module",
  "license": "proprietary",
  "require": {
  },
  "require-dev": {
    "spryker/code-sniffer": "*",
    "spryker/testify": "*"
  },
  "autoload": {
    "psr-4": {
      "{namespace}\\": "src/{namespace}/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "{namespace}Test\\": "tests/{namespace}Test/"
    }
  },
  "minimum-stability": "dev",
  "prefer-stable": true,
  "extra": {
    "branch-alias": {
      "dev-master": "1.0.x-dev"
    }
  },
  "config": {
    "sort-packages": true
  }
}
