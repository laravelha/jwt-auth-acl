# JWT Auth ACL
JWT Auth ACL is a Laravel package to authentication and authorization package.

## How its work
The middleware `acl` check if auth user is authorized to access the route and he is authorized when has permission with 
the same name of the route within any role he has

## How to use
* Install packaqe with composer `composer require laravelha/jwt-auth-acl`
* Set `guards.api.driver` to `api` or Publish config `php artisan vendor:publish --foce --tag jwt-auth-acl-config`
* Run `php artisan db:seed --class=PermissionsTableSeeder` to populate permissions table
* Add middleware `acl` on routes
