[![justforfunnoreally.dev badge](https://img.shields.io/badge/justforfunnoreally-dev-9ff)](https://justforfunnoreally.dev)

# A simple mvc framework
@author Jakob Osterberger

I coded this little framework to get familiar with the MVC pattern and how it is used in PHP applications.

The framework includes a:

### Database class to ease DB access, provides methods to:
- establish and manage multiple DataBase connections

### QueryBuilder class to ease DB access, provides methods to:
- build SQL queries (select, where, insert, update, delete, join, limit, count, orderBy)
- execute SQL queries
- fetch results 
- fetch single result queries
 
### Model - a simple DataClass which provides:
- magic GETTER
- magic SETTER
- static method to map an array to Domain Object
- array access to Domain Object
- serializing to JSON

### BaseRepository
Takes care of CRUD operations and ORM for Domain Objects.
Automatically retrieves all fields & keys of domain in database by naming convention of extended Model
(e.q. 'UserRepository' -> DomainModelName = 'User', DataBaseTableName = 'user'). Provides methods to:
- CREATE new rows in DataBase Table
- RETRIEVE rows from DataBase Table
- UPDATE rows of DataBase Table
- DELETE rows from DataBase Table

### BaseController
- provides a basic implementation of a controller

### Router
- provides methods to register routes
- provides methods to dispatch routes
- provides methods to redirect to routes
- provides methods to register middlewares

### Route
- provides methods to register middlewares
- provides methods to register callbacks (controller methods)

### Request
- Wrapper for PHP's $_SERVER, $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION, $_ENV, $_REQUEST, php://input

### Response
Wrapper for PHP's header() and setcookie() functions
- provides methods to set HTTP status code
- provides methods to set HTTP headers
- provides methods to set HTTP cookies
- provides methods to set HTTP body

### Collection
Provides methods to work with collections and arrays in a functional way (inspired by Laravel's Collection class)

### View
- provides methods to render views
- provides methods to render partials
- provides methods to render layouts

## Development with [REPL](https://github.com/ramsey/composer-repl-lib)

Run the PHP REPL with the following command:
```shell
./vendor/bin/repl
```

## Run Tests
```shell
./vendor/bin/phpstan analyse src SimpleMvc

./vendor/bin/phpunit tests
```