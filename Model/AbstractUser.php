<?php

namespace Chaplean\Bundle\UserBundle\Model;

use Chaplean\Bundle\UserBundle\Model\UserInterface as ChapleanUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;

/**
 * AbstractUser.php
 *
 * @package   Chaplean\Bundle\UserBundle\Model
 * @author    Benoit - Chaplean <benoit@chaplean.com>
 * @copyright 2014 - 2015 Chaplean (http://www.chaplean.com)
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
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=false, name="email")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=false, name="firstname")
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=250, nullable=false, name="lastname")
     */
    protected $lastname;

    /**
     * The salt to use for hashing.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, name="password_salt")
     */
    protected $passwordSalt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, name="password")
     */
    protected $password;

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
    protected $credentialExpired;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="confirmation_token")
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
     * @ORM\Column(type="datetime", nullable=true, name="date_lastlogin")
     */
    protected $dateLastlogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="date_password_request")
     */
    protected $datePasswordRequest;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="date_expired")
     */
    protected $dateExpired;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true, name="date_credential_expired")
     */
    protected $dateCredentialExpired;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passwordSalt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->roles = array();
        $this->credentialExpired = false;
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
     * Set passwordSalt
     *
     * @param string $passwordSalt
     *
     * @return self
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;

        return $this;
    }

    /**
     * Get passwordSalt
     *
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->getPasswordSalt();
    }

    /**
     * @param string|null $salt
     *
     * @return self
     */
    public function setSalt($salt)
    {
        return $this->setPasswordSalt($salt);
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
     * @return bool
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
        return !$this->locked;
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
     * @return bool
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
        return !$this->expired;
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
     * @return bool
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
        return !$this->credentialExpired;
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
        $this->roles = array();

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
     * Set dateLastlogin
     *
     * @param \DateTime $dateLastlogin
     *
     * @return self
     */
    public function setDateLastlogin($dateLastlogin)
    {
        $this->dateLastlogin = $dateLastlogin;

        return $this;
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
        $this->dateLastlogin = $time;

        return $this;
    }

    /**
     * Get dateLastlogin
     *
     * @return \DateTime
     */
    public function getDateLastlogin()
    {
        return $this->dateLastlogin;
    }

    /**
     * Set dateExpired
     *
     * @param \DateTime $dateExpired
     *
     * @return self
     */
    public function setDateExpired($dateExpired)
    {
        $this->dateExpired = $dateExpired;

        return $this;
    }

    /**
     * Get dateExpired
     *
     * @return \DateTime
     */
    public function getDateExpired()
    {
        return $this->dateExpired;
    }

    /**
     * Set dateCredentialExpired
     *
     * @param \DateTime $dateCredentialExpired
     *
     * @return self
     */
    public function setDateCredentialExpired($dateCredentialExpired)
    {
        $this->dateCredentialExpired = $dateCredentialExpired;

        return $this;
    }

    /**
     * Get dateCredentialExpired
     *
     * @return \DateTime
     */
    public function getDateCredentialExpired()
    {
        return $this->dateCredentialExpired;
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
            array(
                $this->password,
                $this->passwordSalt,
                $this->email,
                $this->expired,
                $this->locked,
                $this->credentialExpired,
                $this->enabled,
                $this->id,
            )
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

        list($this->password, $this->passwordSalt, $this->email, $this->expired, $this->locked, $this->credentialExpired, $this->enabled, $this->id) = $data;
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
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = strtoupper($lastname);

        return $this;
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = ucwords(strtolower($firstname));

        return $this;
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
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     * @deprecated Username is replaced by email
     */
    public function getUsername()
    {
        return $this->email;
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
        $username = null;

        return $this;
    }

    /**
     * Gets the canonical username in search and sort queries.
     *
     * @return string
     * @deprecated Username not used in ChapleanUserBundle
     */
    public function getUsernameCanonical()
    {
        return $this->email;
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
        $usernameCanonical = null;

        return $this;
    }

    /**
     * Gets the canonical email in search and sort queries.
     *
     * @return string
     * @deprecated Username not used in ChapleanUserBundle, see email
     */
    public function getEmailCanonical()
    {
        return $this->email;
    }

    /**
     * Sets the canonical email.
     *
     * @param string $emailCanonical
     *
     * @return self
     * @deprecated Username not used in ChapleanUserBundle, see email
     */
    public function setEmailCanonical($emailCanonical)
    {
        $emailCanonical = null;

        return $this;
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
