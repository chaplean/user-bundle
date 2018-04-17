Getting Started With Chaplean User Bundle
=========================================

# Prerequisites

This version of the bundle requires Symfony 3.4+.

# Installation

## 1. Composer

```
composer require chaplean/user-bundle
```

## 2. AppKernel.php

Add
```
            new FOS\UserBundle\FOSUserBundle(),
            new Chaplean\Bundle\UserBundle\ChapleanUserBundle(),
```

## 3. Define User entity

Create a User class with doctrine information.

```php
<?php
//...

use Chaplean\Bundle\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="<YourTableName>")
 */
class User extends BaseUser {
//...
}
```

## 4. Minimal Configuration

Define namespace your user entity in `parameters.yml`:

```yaml
parameters:
#...
    chaplean_user.entity.user.class: '<NamespaceUserEntity>'
```

Import default config in `app/config/config.yml`:

```yaml
imports:
    - { resource: '@ChapleanUserBundle/Resources/config/config.yml' }
```

Define a route name for index path
In `app/config/config.yml`:
```yaml
chaplean_user:
    controller:
        index_route: <YourRouteNameForIndex>
        login_route: <YourRouteNameForLogin>
```

## 5. Configure security

In `app/config/security.yml`:
```yaml
imports:
    - { resource: '@ChapleanUserBundle/Resources/config/security.yml' }
```

If you want you can also overide the defaults :
```yaml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt

    firewalls:
        main:
            pattern: ^/
            form_login:
                login_path: /login
                check_path: /api/login
                use_forward: false
                remember_me: true
                use_referer: true
                success_handler: chaplean_user.authentication.handler_json
                failure_handler: chaplean_user.authentication.handler_json
                csrf_token_generator: security.csrf.token_manager
            logout:
                path: /logout
                target: /
            anonymous:    true
```

## 6. Import routing.yml

You should then create a Controller action for your login page. Make this controller inherit LoginController
to get the checkAction and logoutAction actions. Finally in your routing create a route for these.

In your controller:
```php
<?php

namespace App\Bundle\FrontBundle\Controller;

use Chaplean\Bundle\UserBundle\Controller\LoginController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class LoginController.
 */
class LoginController extends BaseController
{
    /**
     * @var CsrfTokenManagerInterface
     */
    protected $tokenManager;

    /**
     * LoginController constructor.
     *
     * @param CsrfTokenManagerInterface $tokenManager
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * Renders the login page.
     *
     * @Route("/connexion-of")
     *
     * @return Response
     */
    public function loginAction()
    {
        return $this->render(
            'Login/login.html.twig',
            [
                'csrf_token' => $this->tokenManager->getToken('authenticate')->getValue()
            ]
        );
    }
}
```

In `app/config/routing.yml`:
```yaml
chaplean_user_login_check:
    path: /api/login
    defaults: { _controller: AppFrontBundle:Login:check }
    methods: 'POST'

chaplean_user_logout:
    path: /logout
    defaults: { _controller: AppFrontBundle:Login:logout }

chaplean_user:
    resource: '@ChapleanUserBundle/Resources/config/routing.yml'

chaplean_user_api:
    type: rest
    resource: '@ChapleanUserBundle/Resources/config/routing_rest.yml'
    prefix:   /api/

app_front:
    type: annotation
    resource: '@AppFrontBundle/Controller/'
    prefix: /
```

# Events

The UserBundle defines some events to allow you to hook in your own logic:

- ChapleanUserCreatedEvent : Dispatched after a user is created. Use getUser() to retreive the entity.
- ChapleanUserDeletedEvent : Dispatched before a user is deleted. Use getUser() to retreive the entity.
