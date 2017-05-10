<?php

namespace RealWorld\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use RealWorld\Models\User;

/**
 * Class RegisterUserValidator
 * @package RealWorld\Validators
 */
class RegisterUserValidator extends Validation
{
    public function initialize()
    {
        $this
            ->add(
                "username",
                new PresenceOf(
                    [
                        "message" => "can't be blank",
                    ]
                )
            )
            ->add(
                'username',
                new Uniqueness(
                    [
                        "model"   => new User(),
                        "message" => "has already been taken",
                    ]
                )
            )
            ->add(
                "email",
                new PresenceOf(
                    [
                        "message" => "can't be blank",
                    ]
                )
            )
            ->add(
                'email',
                new Uniqueness(
                    [
                        "model"   => new User(),
                        "message" => "has already been taken",
                    ]
                )
            )
            ->add(
                "email",
                new Email(
                    [
                        "message" => "is invalid",
                    ]
                )
            )
            ->add(
                "password",
                new PresenceOf(
                    [
                        "message" => "can't be blank",
                    ]
                )
            );
    }
}