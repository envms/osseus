[![Maintainability](https://api.codeclimate.com/v1/badges/cf807c36168b7242a576/maintainability)](https://codeclimate.com/github/envms/osseus/maintainability)

<h1 align="center">
Osseus<br>
Upgrade Your Legacy PHP Code
</h1>

Osseus is a light and tiny PHP framework/library that integrates with any application.
It provides both a RESTful API and a more "traditional" service to support incremental
code improvements.

Osseus can be used as a full-featured application framework, or simply as a utility
library to help your application scale. Its goal is to be flexible and impartial to
code and database structures.

### Features

- light and fast router with a smart URI parser
- built-in security tools to validate and sanitize data
- interfaces with [FluentPDO](https://github.com/envms/fluentpdo) to provide quick
and seamless database interactions
- an internationalization template system to implement new languages quickly and easily
- classic MVC system with some small additions

### Contributions

Contributors are very welcome to help test and improve Osseus

### Usage

To get started, all you need is a little setup:

```php
// add necessary classes
use Envms\Osseus\Dev\Debug;
use Envms\Osseus\Parse\Uri;
use Envms\Osseus\Router\Route;
use Envms\Osseus\Server\Environment;

// set your environment
$environment = Environment::instance(Environment::DEVELOPMENT); // the current environment
$debug = Debug::instance(Environment::TESTING); // the maximum environment to show debug statements

// optional but recommended - parse the URI and route to the index controller
$uri = new Uri($_SERVER['REQUEST_URI']);
$router = new Route('TestApp');
$router->go($uri);
```

The router will direct the `$uri` to your application's controller and action, and you're on your way!

#### The current version is beta-2 (v0.4.3)
