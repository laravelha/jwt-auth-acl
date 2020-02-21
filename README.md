# JWT Auth ACL
JWT Auth ACL is a Laravel package to authentication and authorization package.

The middleware `ha.acl` check if auth user is authorized to access the route and he is authorized when has permission with 
the same name of the route within any role he has

## Install
* Install packaqe with composer `composer require laravelha/jwt-auth-acl`
* Remove default User files: 
    - `app/User.php` 
    - `database/factories/UserFactory.php` 
    - `database/migrations/2014_10_12_000000_create_users_table.php`
* Publish config `php artisan vendor:publish --foce --tag ha-auth-config`
* Publish seeder `php artisan vendor:publish --foce --tag ha-auth-seeds`
* Add `ha.acl` on routes with that you wish check permissions
* Run `php artisan db:seed --class=PermissionsTableSeeder` to populate permissions table
* Run `php artisan jwt:secret`
* Set `config/l5-swagger.php` to read annotations on `vendor/laravelha/jwt-auth-acl/src`
* Run `php artisan l5-swagger:generate` to generate docs


## Use
* Add `ha.acl` on your protected routes
* Publish seeder `php artisan vendor:publish --foce --tag ha-auth-seeds`
* Run `php artisan db:seed --class=PermissionsTableSeeder` to populate permissions table
* Create roles
```bash
php artisan tinker
factory(Role::class)->create(['name' => 'Name', 'description' => 'Description'])
```

```bash
curl -X POST "APP_URL/api/auth/login" -H "accept: application/json" -H "Content-Type: application/json" -d "{ \"email\": \"admin@laravelha.com\", \"password\": \"password\"}"
curl -X POST "APP_URL/api/auth/roles" -H "accept: application/json" -H "Authorization: Bearer TOKEN" -d "{ \"name\": \"Name\", \"description\": \"Description\"}"
```

* Sync role permissions
```bash
php artisan tinker
$role = Role::find(ID)
$role->permissions->sync([ID_P1, ID_P2, ID_P3..])
```

```bash
curl -X POST "APP_URL/api/auth/login" -H "accept: application/json" -H "Content-Type: application/json" -d "{ \"email\": \"admin@laravelha.com\", \"password\": \"password\"}"
curl -X PUT "APP_URL/api/auth/roles/ID" -H "accept: application/json" -H "Authorization: Bearer TOKEN" -d "{ \"permissions\": \"[ID_P1, ID_P2, ID_P3..]\"}"
```

* Sync user roles
```bash
php artisan tinker
$user = User::find(ID)
$user->roles->sync([ID_R1, ID_R2, ID_R3..])
```

```bash
curl -X POST "APP_URL/api/auth/login" -H "accept: application/json" -H "Content-Type: application/json" -d "{ \"email\": \"admin@laravelha.com\", \"password\": \"password\"}"
curl -X PUT "APP_URL/api/auth/users/ID" -H "accept: application/json" -H "Authorization: Bearer TOKEN" -d "{ \"roles\": \"[ID_R1, ID_R2, ID_R3..]\"}"
```
