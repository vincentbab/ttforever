<?php

namespace Mping\CoreBundle\Validator\Constraints;

use Mping\CoreBundle\Fftt\Service;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FfttLicenceValidator extends ConstraintValidator
{
    private $fftt;

    public function __construct(Service $fftt)
    {
        $this->fftt = $fftt;
    }
    public function validate($value, Constraint $constraint)
    {
        if ($value === null) {
            return;
        }
        
        if (!preg_match('/^[0-9]{4,10}$/', $value)) {
            $this->context->addViolation($constraint->message, array('%value%' => $value));

            return;
        }

        $joueur = $this->fftt->getJoueur($value);
        if (!$joueur || empty($joueur['licence'])) {
            $this->context->addViolation($constraint->message, array('%value%' => $value));
        }
    }
}