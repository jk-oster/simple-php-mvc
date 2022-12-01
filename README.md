[![justforfunnoreally.dev badge](https://img.shields.io/badge/justforfunnoreally-dev-9ff)](https://justforfunnoreally.dev)

# A simple MicroBlog implementing a simple mvc framework
@author Jakob Osterberger

I coded this little framework to get familiar with the MVC pattern and how it is used in PHP applications.

The framework includes a:

### Database class to ease DB access, provides methods to:
- establish / close DataBase connection
- execute SQL queries
- retrieve all results
- retrieve first row of results
- retrieve first column of results
- retrieve first value of results
 
### Model - a simple DataClass wich provides:
- magic GETTER
- magic SETTER
- static method to map an array to Domain Object

### Base Model Class for domain repositories.
Automatically retrieves all fields & keys of domain in database by naming convention of extended Model
(e.q. 'UserRepository' -> DomainClassName = 'User', DataBaseTableName = 'user'). Provides methods to:
- CREATE new rows in DataBase Table
- RETRIEVE rows from DataBase Table
- UPDATE rows of DataBase Table
- DELETE rows from DataBase Table

### Abstract Action Controller.
- Takes request, evaluates which corresponding action should be called
- Checks access permission for action before executing
- Calls matched action

### API Router
- Evaluates requested route and initializes corresponding ApiController

### Base Api Controller
- Takes request and evaluates corresponding endpoint
- Checks access permissions for endpoint before executing
- Calls endpoint