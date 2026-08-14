<?php

namespace App\DTOValidator;

use App\DTO\Input\StoreInputDTOInterface;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class DTOValidator
{
    public function __construct(private ValidatorInterface $validator)
    {}

    public function validate(StoreInputDTOInterface $DTO): void
    {
        $errors = $this->validator->validate($DTO);

        if (count($errors) > 0) {
            $messages = [];

            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()][] = $error->getMessage();
            }
            throw new ValidationException($messages);
        }
    }
}