# PHP DTO

PHP DTO is a simple package that makes populating and manipulating data a breeze.

## Installation

Install this package via Composer to get started.

```bash
composer require deeejmc/php-dto
```

## Usage

In this example, we are creating a simple user DTO that will populate some typical user profile information.

### Example User DTO Class

```php
namespace App\Dto;

use Deeejmc\PhpDto\Abstracts\Dto;

class User extends Dto
{
    public ?int $id = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $email = null;
}
```

### Example User Service Class

```php
namespace App\Services;

use App\Dto\User;

class UserService
{
    public function getUser(): User
    {
        $user = new User();

        $user->fill([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@gmail.com',
        ]);

        // output
        // {
        //     id: 1,
        //     firstName: 'John',
        //     lastName: 'Doe',
        //     email: 'johm.doe@gmail.com'
        // }

        return $user;
    }
}
```

### Example User Helper Class

```php
namespace App\Helpers;

use App\Services\UserService;

class UserHelper
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function getName(): string
    {
        $user = $this->userService->getUser();

        return $user->firstName . ' ' . $user->lastName;
    }
}
```

### Overriding Properties

There may be times when you need to set a fallback, or perform some custom logic, in order to properly set a property value. To avoid potentially duplicating code in your project, you can add an override function in your DTO class. 

These functions take your property name as camel case, prefixed with 'set' and a single nullable parameter for the value.

In the example below, we are overriding the firstName property to either populate the value given or defauling to 'Unknown'.

```php
namespace App\Dto;

use Deeejmc\PhpDto\Abstracts\Dto;

class User extends Dto
{
    public ?string $firstName = null;

    public function setFirstName(string $firstName = null): void
    {
        // if a first name isn't provided, use the fallback
        $this->firstName = $firstName ?: 'Unknown';
    }
}
```

### Mapping Properties

Say you are processing data from an API and one of the attributes you get back contains a user's email address. The key the API provides is `email_address` but the property you have in your DTO is `email`. To avoid creating multiple variations of the same variable and keeping all references to properties consistent, you can easily map properties to incoming attributes.

```php
namespace App\Services;

use App\Dto\User;

class UserService
{
    public function getUser(): User
    {
        $apiResponse = [
           'first_name' => 'John',
           'last_name' => 'Doe',
           'email_address' => 'john.doe@gmail.com',
        ];

        $user = new User();

        $user->map([
            'email' => 'email_address',
        ]);

        $user->fill($apiResponse);

        // output
        // {
        //     firstName: 'John',
        //     lastName: 'Doe',
        //     email: 'johm.doe@gmail.com'
        // }

        return $user;
    }
}
```

### Converting to an Array

It's really easy to convert your DTO object to an array.

```php
$user = new User();

$user->fill($attributes);

return $user->toArray();
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

## License

[MIT](https://choosealicense.com/licenses/mit/)