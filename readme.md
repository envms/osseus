[![Maintainability](https://api.codeclimate.com/v1/badges/cf807c36168b7242a576/maintainability)](https://codeclimate.com/github/envms/osseus/maintainability)

# Osseus Framework

#### Current version is beta-1 (v0.3.0)

Osseus is a light and tiny PHP framework and toolbox. Its goal is to be flexible and impartial to code and database
structures. It interfaces with FluentPDO 2.0 to provide quick and seamless database interactions.

Osseus can be used as a standard application framework or simply as a utility library.

### Features

- light and fast router with a smart URI parser
- built-in security tools to validate and sanitize data
- an Internationalization template system to implement new languages quickly and easily
- classic MVC system with some small additions

### Contributions
Contributors are more than welcome to help test and improve Osseus

### Usage

To get started, all you need is a little setup:

```php
// add necessary classes
use Envms\Osseus\Parse\Uri;
use Envms\Osseus\Router\Route;
use Envms\Osseus\Server\Environment;

// set your environment
$environment = Environment::instance();
$environment->init(Environment::DEVELOPMENT);

// parse the URI and route to a controller
$uri = new Uri($_SERVER['REQUEST_URI']);
$router = new Route('TestApp');
$router->go($uri);
```

The router will direct the `$uri` to your application's controller and action, and you're on your way! Documentation is coming soon.
