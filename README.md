# Monolog Understand.io handler 

[![Build Status](https://travis-ci.org/understand/understand-monolog.svg)](https://travis-ci.org/understand/understand-monolog)
[![Latest Stable Version](https://poser.pugx.org/understand/understand-monolog/v/stable.svg)](https://packagist.org/packages/understand/understand-monolog) 
[![Latest Unstable Version](https://poser.pugx.org/understand/understand-monolog/v/unstable.svg)](https://packagist.org/packages/understand/understand-monolog) 
[![License](https://poser.pugx.org/understand/understand-monolog/license.svg)](https://packagist.org/packages/understand/understand-monolog)
[![HHVM Status](http://hhvm.h4cc.de/badge/understand/understand-monolog.svg)](http://hhvm.h4cc.de/package/understand/understand-monolog)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/understand/understand-monolog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/understand/understand-monolog/?branch=master)

### Introduction

This package provides a [Monolog](https://github.com/Seldaek/monolog) handler and formatter for log data delivery to [Understand.io](https://www.understand.io).


### Quick start

1. Install package

```
composer require understand/understand-monolog
```

2. Add an Understand handler to Monolog
```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// input token from Understand.io
$inputToken = 'ab1cd234-1234-45e6-789f-gh1fa1234567';

// choose a handler, either async or sync (see below)
$understandAsyncHandler = new UnderstandMonolog\Handler\UnderstandAsyncHandler($inputToken); // async handler
$understandSyncHandler = new UnderstandMonolog\Handler\UnderstandSyncHandler($inputToken); // sync handler

$monologLogger = new Logger('name');
$monologLogger->pushHandler($understandAsyncHandler); // or $understandSyncHandler

$monologLogger->addError('first error');
```

### Handlers

##### UnderstandSyncHandler
The sync handler uses the [PHP Curl](http://php.net/manual/en/book.curl.php) extension and delivers logs synchronously to Understand.io. This means that if your application generates a large amount of data it could slow down your app.

##### UnderstandAsyncHandler
We recommend making use of the `async` handler where possible. It is supported in most systems - the only requirement is that CURL command line tool is installed and functioning correctly. To check whether CURL is available on your system, execute the following command in your console
```
curl -h
```
If you see instructions on how to use CURL then your system has the CURL binary installed and you can use the async handler.



### Exception encoder
This helper class allows you to serialize PHP exceptions as an array which can be then serialized to json. The main benefit of doing this is that Understand will then be able to parse your logs more intelligently, allowing for better search and filtering capabilities.

```php
$exception = new \DomainException('This is Exception', 123);

$encoder = new \UnderstandMonolog\Encoder\ExceptionEncoder();
$array = $encoder->exceptionToArray($exception);

print_r($array);exit;

//Array
//(
//    [message] => This is Exception
//    [class] => DomainException
//    [code] => 123
//    [file] => /home/vagrant/share/understand-lumen-test/app/Exceptions/Handler.php
//    [line] => 30
//    [stack] => Array
//        (
//            [0] => Array
//                (
//                    [class] => App\Exceptions\Handler
//                    [function] => report
//                    [args] => Array
//                        (
//                            [0] => DomainException
//                        )
//
//                    [type] => method
//                    [file] => /home/vagrant/share/understand-lumen-test/vendor/laravel/lumen-framework/src/Application.php
//                    [line] => 354
// .......... and more

```

#### How to use the Exception encoder

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// input token from Understand.io
$inputToken = 'ab1cd234-1234-45e6-789f-gh1fa1234567';

// choose a handler
$understandAsyncHandler = new UnderstandMonolog\Handler\UnderstandAsyncHandler($inputToken); // async handler

$monologLogger = new Logger('name');
$monologLogger->pushHandler($understandAsyncHandler);

$exception = new \DomainException('This is Exception', 123);

$encoder = new UnderstandMonolog\Encoder\ExceptionEncoder();
$context = $encoder->exceptionToArray($exception);

$monologLogger->addError($exception->getMessage(), $context);
```

### License

The Laravel Understand.io Monolog package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
