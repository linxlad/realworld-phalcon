<?php

namespace RealWorld\Models;

use Firebase\JWT\JWT;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Security;
use RealWorld\Validators\RegisterUser;

/**
 * Class User
 * @package RealWorld\Models
 */
class User extends Model implements \JsonSerializable
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=20, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $username;

    /**
     *
     * @var string
     * @Column(type="string", length=48, nullable=false)
     */
    protected $email;

    /**
     *
     * @var string
     * @Column(type="string", length=128, nullable=false)
     */
    protected $password;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $bio;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $image;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $token;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $token_expires;

    /**
     *
     * @var string
     * @Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     *
     * @var string
     * @Column(type="datetime", nullable=true)
     */
    protected $modified;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new RegisterUser();

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->addBehavior(
            new Timestampable(
                [
                    "beforeCreate" => [
                        "field"  => "created",
                        "format" => 'Y-m-d H:i:s'
                    ],
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    "beforeCreate" => [
                        "field"  => "modified",
                        "format" => 'Y-m-d H:i:s'
                    ],
                ]
            )
        );

        $this->setSchema("realworlddb");
        $this->hasMany('id', 'Articles', 'user_id', ['alias' => 'Articles']);
        $this->hasMany('id', 'Comments', 'user_id', ['alias' => 'Comments']);
        $this->hasMany('id', 'Favorites', 'user_id', ['alias' => 'Favorites']);
        $this->hasMany('id', 'Follows', 'follower_id', ['alias' => 'Follows']);
        $this->hasMany('id', 'Follows', 'followed_id', ['alias' => 'Follows']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * @param mixed $data
     * @param mixed $whiteList
     * @return User|bool
     */
    public function create($data = null, $whiteList = null)
    {
        if (!parent::create($data, $whiteList)) {
            return false;
        }

        return $this;
    }

    /**
     *
     */
    public function beforeSave()
    {
        // Convert the array into a string
        $this->token = $this->generateJWT();
    }

    /**
     * @return string
     */
    private function generateJWT()
    {
        // Encode the token which will expire 60 days from yesterday.
        $timestamp = time()-86400;
        $token = [
            'id' => $this->getUsername(),
            'exp' => strtotime("+7 day", $timestamp)
        ];
        $key = $this->getDI()->get('config')->application->security->salt;

        return JWT::encode($token, $key);
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password)
    {
        $security = new Security();
        $this->password = $security->hash($password);

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $bio
     * @return User
     */
    public function setBio(string $bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $image
     * @return User
     */
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Build JSON object.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'user' => [
                'id' => $this->getId(),
                'email' => $this->getEmail(),
                'username' => $this->getUsername(),
                'bio' => $this->getBio(),
                'image' => $this->getImage(),
                'token' => $this->getToken(),
                'createdAt' => $this->created,
                'updatedAt' => $this->modified,
            ]
        ];
    }
}
