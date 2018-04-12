<?php

namespace Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UserBundle\Model\UserInterface as ChapleanUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;

/**
 * AbstractUser.php
 *
 * @package   Chaplean\Bundle\UserBundle\Model
 * @author    Benoit - Chaplean <benoit@chaplean.coop>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.coop)
 * @since     1.0.0
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractUser implements ChapleanUserInterface
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", unique=false, length=250, nullable=true)
     */
    protected $usernameCanonical;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=false, name="email")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", unique=true, length=250, nullable=false)
     */
    protected $emailCanonical;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, name="password")
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="date_last_login")
     */
    protected $dateLastLogin;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, name="is_enabled")
     */
    protected $enabled = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, name="is_locked")
     */
    protected $locked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, name="is_expired")
     */
    protected $expired = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false, name="is_credential_expired")
     */
    protected $credentialExpired = false;

    /**
     * @ORM\Column(type="string", nullable=true, name="salt")
     */
    protected $salt;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=180, nullable=true, name="confirmation_token")
     */
    protected $confirmationToken;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=false, name="roles")
     */
    protected $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false, name="date_add")
     * @Gedmo\Timestampable(on="create")
     */
    protected $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="date_update")
     */
    protected $dateUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="date_password_request")
     */
    protected $datePasswordRequest;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->credentialExpired = false;
        $this->roles = [];
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     * @deprecated Username is replaced by email
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return self
     * @deprecated Username not used in ChapleanUserBundle, see email
     */
    public function setUsername($username)
    {
        return $this->setEmail($username);
    }

    /**
     * Gets the canonical username in search and sort queries.
     *
     * @return string
     * @deprecated Username not used in ChapleanUserBundle
     */
    public function getUsernameCanonical()
    {
        return $this->getEmailCanonical();
    }

    /**
     * Sets the canonical username.
     *
     * @param string $usernameCanonical
     *
     * @return self
     * @deprecated Username not used in ChapleanUserBundle, see email
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        return $this->setEmailCanonical($usernameCanonical);
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the canonical email.
     *
     * @param string $emailCanonical
     *
     * @return self
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = strtolower($emailCanonical);

        return $this;
    }

    /**
     * Gets the canonical email in search and sort queries.
     *
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the plain password.
     *
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Gets the plain password.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param boolean $enabled
     *
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Sets the salt.
     *
     * @param string $salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Gets the salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param boolean $locked
     *
     * @return self
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return boolean true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return !$this->isLocked();
    }

    /**
     * @param boolean $expired
     *
     * @return self
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return $this->expired;
    }
    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return boolean true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return !$this->isExpired();
    }

    /**
     * @param boolean $credentialExpired
     *
     * @return self
     */
    public function setCredentialExpired($credentialExpired)
    {
        $this->credentialExpired = $credentialExpired;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isCredentialExpired()
    {
        return $this->credentialExpired;
    }
    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return boolean true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return !$this->isCredentialExpired();
    }

    /**
     * Sets the confirmation token
     *
     * @param string $confirmationToken
     *
     * @return self
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Gets the confirmation token.
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array [string] $roles
     *
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * Adds a role to the user.
     *
     * @param string $role
     *
     * @return self
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Removes a role to the user.
     *
     * @param string $role
     *
     * @return self
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return self
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return self
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set dateLastLogin
     *
     * @param \DateTime $dateLastLogin
     *
     * @return self
     */
    public function setDateLastLogin($dateLastLogin)
    {
        return $this->setLastLogin($dateLastLogin);
    }

    /**
     * Sets the last login time
     *
     * @param \DateTime $time
     *
     * @return self
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->dateLastLogin = $time;

        return $this;
    }

    /**
     * Get dateLastLogin
     *
     * @return \DateTime
     */
    public function getDateLastLogin()
    {
        return $this->dateLastLogin;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(
            [
                $this->password,
                $this->salt,
                $this->email,
                $this->emailCanonical,
                $this->expired,
                $this->locked,
                $this->credentialExpired,
                $this->enabled,
                $this->id,
            ]
        );
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->password,
            $this->salt,
            $this->email,
            $this->emailCanonical,
            $this->expired,
            $this->locked,
            $this->credentialExpired,
            $this->enabled,
            $this->id
        ) = $data;
    }

    /**
     * Checks whether the password reset request has expired.
     *
     * @param integer $ttl Requests older than this many seconds will be considered expired
     *
     * @return boolean true if the user's password request is non expired, false otherwise
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return !($this->getPasswordRequestedAt() instanceof \DateTime) || ((time() - $this->getPasswordRequestedAt()->getTimestamp() < $ttl));
    }

    /**
     * Checks whether the account create request has expired.
     *
     * @param integer $ttl Requests older than this many seconds will be considered expired
     *
     * @return boolean true if the user's account request is expired, false otherwise
     */
    public function isAccountExpired($ttl)
    {
        return ($this->getDateAdd() instanceof \DateTime && time() - $this->getDateAdd()->getTimestamp() > $ttl);
    }

    /**
     * Sets the timestamp that the user requested a password reset.
     *
     * @param null|\DateTime $date
     *
     * @return self
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->datePasswordRequest = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->datePasswordRequest;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Tells if the the given user has the super admin role.
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    /**
     * Sets the super admin status
     *
     * @param boolean $boolean
     *
     * @return self
     */
    public function setSuperAdmin($boolean)
    {
        if ($boolean) {
            $this->addRole(self::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(self::ROLE_SUPER_ADMIN);
        }

        return $this;
    }
}
