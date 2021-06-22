# cartrack
Simple crud api with search function

How to install
--------------
1. Clone inside cartack/api/v1 directory.
2. Run `composer install`
3. Rename .env.example to .env and update db credentials.

```
#  Database setup
DB_DNS="pgsql:host={host};port={port};dbname={dbname};sslmode=require"
DB_USERNAME="username"
DB_PASSWORD="password"

# JWT setup
SECRET_KEY="cartrack_secret_key"
ALGORITHM="HS256"

# JWT payload setup
EXPIRATION=60
NOT_BEFORE=10
```

End points
----------

Generate Token
```
POST localhost/cartrack/api/v1/login/

body
{
    "username" : "admin",
    "password" : "admin"
}

validation
-username and password are required
```

Create
```
// create a new record
POST localhost/cartrack/api/v1/persons/

headers
Authorization : {generated_token}

body
{
    "first_name" : "John",
    "last_name"  : "Doe",
    "email"      : "johndoe@email.com",
    "birth_date" : "2000-04-01"
}

validation
- all params are required
- email format
- date format
- unique email
```

Read
```
//return all records
GET localhost/cartrack/api/v1/persons

// return a specific record
GET localhost/cartrack/api/v1/persons/{id}

headers
Authorization : {generated_token}
```

Update
```
// update an existing record
PUT localhost/cartrack/api/v1/persons/{id}

headers
Authorization : {generated_token}

body
{
    "first_name" : "John",
    "last_name"  : "Doe",
    "email"      : "johndoe@email.com",
    "birth_date" : "2000-04-01"
}

validation
- all params are required
- email format
- date format
- unique email
```

Delete
```
// delete an existing record
DELETE localhost/cartrack/api/v1/persons/{id}

headers
Authorization : {generated_token}
```

Filtering
---------

```
//get data based on search value
GET localhost/cartrack/api/v1/persons?column_name=value&column_name2=value2&column_name3=value3

//column_names
- first_name
- last_name
- email
- birth_date

//get data between 2 dates (birth_date)
GET localhost/cartrack/api/v1/persons?birth_date_from=yyyy-mm-dd&birth_date_to=yyyy-mm-dd

// both filter must be present
- birth_date_from (ignored if birth_date filter is present)
- birth_date_to (ignored if birth_date filter is present)
```





