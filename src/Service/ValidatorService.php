<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{

    private $validator;

    /**
     * ValidatorService constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator){
        $this->validator = $validator;
    }

    /**
     * Validate Entity & throws a collective validation errors as bad request if fails
     *
     * @param $entity
     * @return bool
     */
    public function Validator($entity): bool
    {
        $validationErrors = [];
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {

            foreach ($errors as $validationError) {
                array_push($validationErrors, $validationError->getMessage());
            }

            throw new HttpException(Response::HTTP_BAD_REQUEST, json_encode($validationErrors));
        }
        return true;
    }

}