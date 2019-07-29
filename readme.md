## Lapas
You can use this project as a lumen passport sample project or just test it as a full Restful project.

## Installation
Clone project:
```bash
git clone git@github.com:thisismahabadi/lupas.git
```

Run:
```bash
composer install
```

Create .env file and Copy .env.example to it:
```bash
cp .env.example .env
```

Migrate the database using:
```bash
php artisan migrate
```

Create the encryption keys needed to generate secure access tokens:
```bash
php artisan passport:install
```

and Finally serve the project:
```bash
php -S localhost:8000 -t public
```

## Back-End Documentation

### Routes and Methods

Available routes and http methods:

```bash
POST: /api/v1/register - Register new user
POST: /api/v1/login - Login user
POST: /api/v1/logout - Logout from current user
```

This route works based on parameters:

```bash
GET: /api/v1/posts - Display a listing of the post
GET: /api/v1/posts?page={pageNumber} - Paginate the listing of the post
GET: /api/v1/posts?filter={columnName} - Filter the listing of the post
GET: /api/v1/posts?field={columnName}&value={orderingValue} - Sort the listing of the post
GET: /api/v1/posts?search={recordValues} - Search in the listing of the post
```

Or you can use every one of these routes together, like this:

```bash
GET /api/v1/posts?page=1&field=id&value=desc&search=hello&filter=id
```

These routes need sending datas in body:

```bash
POST: /api/v1/posts - Store a newly created post in database
GET: /api/v1/posts/{id} - Display the specified post
PUT: /api/v1/posts/{id} - Update the specified post in database
DELETE: /api/v1/posts/{id} - Remove the specified post from database

POST: /api/v1/refresh - Exchange a refresh token for an access token when the access token has expired
```

All of these routes except register and login and logout  are provided with auth:api middleware which means you should send Authorization field in request header.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Alternatives
Also I used [lumen-passport](https://github.com/dusterio/lumen-passport) for use Laravel Passport with Lumen, and Thanks to [phpdoc](https://docs.phpdoc.org/getting-started/your-first-set-of-documentation.html) to help me for writing comments for my code.