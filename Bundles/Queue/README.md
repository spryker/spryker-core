# Queue Module
[![Build Status](https://travis-ci.org/spryker/queue.svg)](https://travis-ci.org/spryker/queue)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)

Queue module provides a set of APIs and Commands for sending/receiving messages to/from queues for other bundles in Spryker. The Queue System provides a protocol for managing asynchronous processing, asynchronous processing in the sense that the sender and receiver do not have access to the same message at the same time. The sender produces a message and sends it to the message box, later when the receiver connects to the message box the message is received.

## Installation

```
composer require spryker/queue
```

## Documentation

[Module Documentation](https://academy.spryker.com/developing_with_spryker/module_guide/queue/queue.html)
