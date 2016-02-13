Bonjour,

Pour réinitialiser votre mot de passe, merci de vous rendre sur <?php echo $view['router']->generate('resetPassword', array('token' => $user->getConfirmationToken()), true) ?>


Si vous n'avez pas demandé à réinitialiser votre mot de passe, merci d'ignorer cet email, votre mot de passe actuel est conservé et fonctionne toujours.

Cordialement,

TTForever.net