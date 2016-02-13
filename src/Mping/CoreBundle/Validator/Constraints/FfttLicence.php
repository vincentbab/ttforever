<?php

namespace Mping\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FfttLicence extends Constraint
{
    public $message = "Ce numéro de licence n'est pas valide";

    public function validatedBy()
    {
        return 'fftt_licence';
    }
}