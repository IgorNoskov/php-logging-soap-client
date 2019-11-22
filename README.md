# PHP Logging SOAP Client

Decorator that allows you to log SOAP requests and responses. PSR-3 compatible. 
You can use it, for example, with Monolog.

[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://doge.mit-license.org)

## Requirements

* PHP 7.1 or higher
* Composer for installation

## Installation

```
composer require "igor-noskov/logging-soap-client"
```

## Usage
You can use it like this:

```php
<?php

use IgorNoskov\LoggingSoapClient\LoggingSoapClient;
use Psr\Log\LoggerInterface;

class Foo
{
    public function doSomething(string $wsdl, LoggerInterface $logger)
    {
        $soapClient = new LoggingSoapClient(new SoapClient($wsdl, ['trace' => true]), $logger);

        // do something useful
    }
}

```
