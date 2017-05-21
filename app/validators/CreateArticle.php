<?php

namespace RealWorld\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator;

/**
 * Class CreateArticle
 * @package RealWorld\Validators
 */
class CreateArticle extends Validation
{
    public function initialize()
    {
        $this
            ->add(
                'title',
                new Validator\PresenceOf([
                    "message" => "can't be blank",
                ])
            )
            ->add(
                'title',
                new Validator\StringLength([
                    'max' => 255,
                    'message' => 'is too long'
                ])
            )
            ->add(
                'description',
                new Validator\PresenceOf([
                    "message" => "can't be blank",
                ])
            )
            ->add(
                "description",
                new Validator\StringLength([
                    'max' => 255,
                    'message' => 'is too long'
                ])
            )
            ->add(
                'body',
                new Validator\PresenceOf([
                    "message" => "can't be blank",
                ])
            )
        ;
    }
}