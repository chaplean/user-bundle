Getting Started With ChapleanUserBundle
=======================================

# Prerequisites

This version of the bundle requires Symfony 2.8+.

### Add ChapleanUserBundle

Include ChapleanUserBundle in `composer.json`

``` json
{
"prefer-stable": true,
"minimum-stability": "dev",
...
"require": {
        "chaplean/user-bundle": "^2.0"
        ...
        }
}
```

Add bundle in `AppKernel.php`

```php
<?php
    //...
    public function registerBundles()
    {
        return array (
            //...
            new FOS\UserBundle\FOSUserBundle(),
            new Chaplean\Bundle\UserBundle\ChapleanUserBundle(),
        );
    }
```

### Define User entity

Create a User class with doctrine information.

```php
<?php
//...

use Chaplean\Bundle\UserBundle\Doctrine\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="<YourTableName>")
 */
class User extends BaseUser {
//...
}
```

###### Note:
If you want add method in user repository, just extends your repository with `Chaplean\Bundle\UserBundle\Repository\UserRepository`.

### Configuration minimal

Define namespace your user entity in `parameters.yml`:

```yaml
parameters:
#...
    chaplean_user.entity.user.class: <NamespaceUserEntity>
```

Import default config in `app/config/config.yml`:

```yaml
imports:
    - { resource: @ChapleanUserBundle/Resources/config/config.yml }
```

Define a route name for index path
In `app/config/config.yml`:
```yaml
chaplean_user:
    controller:
        index_route: <YourRouteNameForIndex>
```

### Configure security

In `app/config/security.yml`:
```yaml
imports:
    - { resource: @ChapleanUserBundle/Resources/config/security.yml }
    
security:
    access_control:
        - { path: ^/index$,         role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/login$,         role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register,       role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/forgot,         role: IS_AUTHENTICATED_ANONYMOUSLY }

    encoders:
        <NamespaceUserEntity>:
            algorithm:            pbkdf2
            hash_algorithm:       sha512
            encode_as_base64:     true
            iterations:           1000

    firewalls:
        main:
            form_login:
                success_handler: chaplean_user.authentication.handler_<http|json>
                failure_handler: chaplean_user.authentication.handler_<http|json>
```



### Import routing.yml

In `app/config/routing.yml`:
```yaml
fos_user:
    resource: @FOSUserBundle/Resources/config/routing/all.xml

chaplean_user:
    resource: @ChapleanUserBundle/Resources/config/routing.yml
```