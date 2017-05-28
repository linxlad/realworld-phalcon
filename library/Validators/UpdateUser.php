<?php

namespace RealWorld\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Url;

/**
 * Class UpdateUser
 * @package RealWorld\Validators
 */
class UpdateUser extends Validation
{
    public function initialize()
    {
        $this
            ->add(
                'username',
                new Uniqueness([
                    "model"   => $this->getEntity(),
                    "message" => "has already been taken",
                ])
            )

            ->add(
                'email',
                new Uniqueness([
                    "model"   => $this->getEntity(),
                    "message" => "has already been taken",
                ])
            )
            ->add(
                "email",
                new Email([
                    "message" => "is invalid",
                ])
            )
            ->add(
                'bio',
                new StringLength([
                    'max' => 255,
                    'message' => 'is too long',
                    'allowEmpty' => true
                ])
            )
            ->add('image',
                new Url([
                    'max' => 255,
                    'message' => 'is not a url',
                    'allowEmpty' => true
                ])
            )
        ;
    }
}