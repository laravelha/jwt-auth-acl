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

### Create roles
Tinker
```bash
php artisan tinker
factory(Role::class)->create(['name' => 'Name', 'description' => 'Description'])
```
GuzzleHttp
```php
$client = new GuzzleHttp\Client(['base_uri' => APP_URL]);
$client->post('/api/roles', [
    'headers' => ['Authorization': 'BEARER '.TOKEN],
    'json' => [
        'name': 'Name',
        'descriptiom': 'Descriptiom',
    ],
]
```
axios
```js
axios.post('/api/auth/login', {
   email: 'admin@laravelha.com', 
   password: 'password', 
});

axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;

axios.post('/api/auth/roles', {
    name: 'Name',
    description: 'Description'
});
cUrl
```bash
curl -X POST "APP_URL/api/auth/login" -H "accept: application/json" -H "Content-Type: application/json" -d "{ \"email\": \"admin@laravelha.com\", \"password\": \"password\"}"
curl -X POST "APP_URL/api/auth/roles" -H "accept: application/json" -H "Authorization: Bearer TOKEN" -d "{ \"name\": \"Name\", \"description\": \"Description\"}"
``````

### Sync role permissions
Tinker
```bash
php artisan tinker
$role = Role::find(ID)
$role->permissions->sync([ID_P1, ID_P2, ID_P3..])
```
GuzzleHttp
```php
$client = new GuzzleHttp\Client(['base_uri' => APP_URL]);
$client->post('/api/roles', [
    'headers' => ['Authorization': 'BEARER '.TOKEN],
    'json' => [
        'permissions': [ID_P1, ID_P2, ID_P3..]
    ],
]
```
axios
```js
axios.post('/api/auth/login', {
   email: 'admin@laravelha.com', 
   password: 'password', 
});

axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;

axios.put('/api/auth/roles/' + ID, {
    permissions: [ID_P1, ID_P2, ID_P3..],
});
```
cUrl
```bash
curl -X POST "APP_URL/api/auth/login" -H "accept: application/json" -H "Content-Type: application/json" -d "{ \"email\": \"admin@laravelha.com\", \"password\": \"password\"}"
curl -X PUT "APP_URL/api/auth/roles/ID" -H "accept: application/json" -H "Authorization: Bearer TOKEN" -d "{ \"permissions\": \"[ID_P1, ID_P2, ID_P3..]\"}"
```

### Sync user roles
Tinker
```bash
php artisan tinker
$user = User::find(ID)
$user->roles->sync([ID_R1, ID_R2, ID_R3..])
```
GuzzleHttp
```php
$client = new GuzzleHttp\Client(['base_uri' => APP_URL]);
$client->post('/api/users', [
    'headers' => ['Authorization': 'BEARER '.TOKEN],
    'json' => [
        'permissions': [ID_R1, ID_R2, ID_R3..]
    ],
]
```
axios
```js
axios.post('/api/auth/login', {
   email: 'admin@laravelha.com', 
   password: 'password', 
});

axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;

axios.put('/api/auth/users/' + ID, {
    permissions: [ID_R1, ID_R2, ID_R3..],
});
```
cUrl
```bash
curl -X POST "APP_URL/api/auth/login" -H "accept: application/json" -H "Content-Type: application/json" -d "{ \"email\": \"admin@laravelha.com\", \"password\": \"password\"}"
curl -X PUT "APP_URL/api/auth/users/ID" -H "accept: application/json" -H "Authorization: Bearer TOKEN" -d "{ \"roles\": \"[ID_R1, ID_R2, ID_R3..]\"}"
```
