<?php

namespace Mping\CoreBundle\Controller;

use Mping\CoreBundle\Entity\Favorite;

use Mping\CoreBundle\Form\Type\ResetPasswordFormType;
use Mping\CoreBundle\Form\Type\SettingsFormType;
use Mping\CoreBundle\Form\Type\ChangePasswordFormType;
use Mping\CoreBundle\Form\Type\RegistrationFormType;
use Mping\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function registerAction()
    {
        $user = new User();
        $form = $this->createForm(new RegistrationFormType(), $user);

        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            throw new NotFoundHttpException();

            $encoder = $this->get('security.encoder_factory')->getEncoder($user);

            $user->setSalt(md5(uniqid(mt_rand(), true)));

            $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            $user->eraseCredentials();

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Votre compte a bien été créé. Vous pouvez maintenant vous connecter.');

            return $this->redirect($this->generateUrl('home'));
        }

        return $this->render('MpingCoreBundle:User:register.html.php', array('form' => $form->createView()));
    }

    public function resetPasswordRequestAction()
    {
        if ($this->getRequest()->isMethod('POST')) {
            $email = $this->getRequest()->request->get('email');

            $user = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:User')->findOneByEmail($email);

            if (null === $user) {
                return $this->render('MpingCoreBundle:User:resetPasswordRequest.html.php', array(
                    'error' => "Cette adresse email n'est associé à aucun compte",
                    'email' => $email
                ));
            }

            if ($user->isPasswordRequestNonExpired(86400)) {
                return $this->render('MpingCoreBundle:User:resetPasswordRequest.html.php', array(
                    'error' => "Une demande de reinitialisation de mot de passe est déjà en cours pour cette adresse",
                    'email' => $email
                ));
            }

            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->generateToken());
            }

            $message = \Swift_Message::newInstance()
                ->setSubject('Reinitialisation de votre mot de passe')
                ->setFrom('noreply@ttforever.net')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('MpingCoreBundle:User:resetPasswordEmail.html.php', array('user' => $user))
                );
            $this->get('mailer')->send($message);

            $user->setPasswordRequestedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            return new RedirectResponse($this->generateUrl('resetPasswordRequestSent',
                array('email' => $this->getObfuscatedEmail($email))
            ));
        }

        return $this->render('MpingCoreBundle:User:resetPasswordRequest.html.php', array('email' => ''));
    }

    public function resetPasswordRequestSentAction($email)
    {
        return $this->render('MpingCoreBundle:User:resetPasswordRequestSent.html.php', array('email' => $email));
    }

    public function getObfuscatedEmail($email)
    {
        return $email;
    }

    public function generateToken()
    {
        return md5(uniqid(mt_rand(), true));
    }

    public function resetPasswordAction($token)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:User')->findOneByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $form = $this->createForm(new ResetPasswordFormType(), $user);

        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);

            $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            $user->eraseCredentials();
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);

            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Votre mot de passe a bien été réinitialisé');

            return new RedirectResponse($this->generateUrl('userLogin'));
        }

        return $this->render('MpingCoreBundle:User:resetPassword.html.php', array('form' => $form->createView()));
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ($error) {
            $error = $this->container->getParameter('kernel.debug') ? $error->getMessage() : "E-mail de connexion ou mot de passe incorrect";
        }

        return $this->render('MpingCoreBundle:User:login.html.php', array(
            'error' => $error,
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
        ));
    }

    public function profileAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('userLogin'));
        }

        if (!$user->getLicence()) {
            return $this->redirect($this->generateUrl('settings'));
        }

        return $this->forward('MpingCoreBundle:Home:player', array('licence' => $user->getLicence()));
    }
    
    public function profileClubAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('userLogin'));
        }

        if (!$user->getLicence()) {
            return $this->redirect($this->generateUrl('settings'));
        }

        $player = $this->get('fftt')->getJoueur($user->getLicence());
        
        return $this->forward('MpingCoreBundle:Home:club', array('numero' => $player['nclub']));
    }

    public function settingsAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('userLogin'));
        }

        $settingsForm = $this->createForm(new SettingsFormType(), $user);
        $changePasswordForm = $this->createForm(new ChangePasswordFormType(), $user);
        
        $settingsForm->handleRequest($this->getRequest());
        $changePasswordForm->handleRequest($this->getRequest());

        if ($settingsForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Les paramètres ont bien été enregisrés');

            return $this->redirect($this->generateUrl('profile'));
        }
        
        if ($changePasswordForm->isValid()) {
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);

            $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            $user->eraseCredentials();
            
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Votre mot de passe a bien été changé');

            return $this->redirect($this->generateUrl('settings'));
        }

        return $this->render('MpingCoreBundle:User:settings.html.php', array(
            'user' => $user,
            'settingsForm' => $settingsForm->createView(),
            'changePasswordForm' => $changePasswordForm->createView(),
        ));
    }

    public function favoritesAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('userLogin'));
        }

        $repo = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:Favorite');
        $favorites = $repo->findByUser($user);
        
        $favoritePlayers = $favoriteClubs = array();
        
        foreach($favorites as $f) {
            if ($f->getType() == 'player') {
                $favoritePlayers[] = $f;
            } else if ($f->getType() == 'club') {
                $favoriteClubs[] = $f;
            }
        }

        return $this->render('MpingCoreBundle:User:favorites.html.php', array(
            'favoritePlayers' => $favoritePlayers,
            'favoriteClubs' => $favoriteClubs,
        ));
    }

    public function addFavoriteAction()
    {
        $error = new JsonResponse(array('message' => "Impossible d'ajouter le favoris"));

        $user = $this->getUser();
        if (!$user) {
            return $error;
        }

        $type = $this->getRequest()->request->get('type');
        $id = $this->getRequest()->request->get('id');
        $name = $this->getRequest()->request->get('name');
        
        $favorite = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:Favorite')->findOneByUser($user, $type, $name);
        
        if ($favorite) {
            $this->getDoctrine()->getManager()->remove($favorite);
            $this->getDoctrine()->getManager()->flush();
            
            return new JsonResponse(array('message' => "Le favoris a bien été supprimé"));
        }

        switch($type) {
            case 'player':
                $favorite = new Favorite();
                $favorite->setUser($user);
                $favorite->setType($type);
                $favorite->setRoute('player');
                $favorite->setName($name);
                $favorite->setParams(array('licence' => $id));

                $this->getDoctrine()->getManager()->persist($favorite);
                $this->getDoctrine()->getManager()->flush();
                break;
            case 'club':
                $favorite = new Favorite();
                $favorite->setUser($user);
                $favorite->setType($type);
                $favorite->setRoute('club');
                $favorite->setName($name);
                $favorite->setParams(array('numero' => $id));

                $this->getDoctrine()->getManager()->persist($favorite);
                $this->getDoctrine()->getManager()->flush();
                break;
            default:
                return $error;
        }

        return new JsonResponse(array('message' => "Le favoris a bien été ajouté"));
    }
    
    public function removeFavoriteAction()
    {
        $error = new JsonResponse(array('message' => "Impossible de supprimer le favoris"));

        $user = $this->getUser();
        if (!$user) {
            return $error;
        }

        $id = (int)$this->getRequest()->request->get('id');

        $favorite = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:Favorite')->find($id);
        
        if (!$favorite) {
            return $error;
        }
        
        $this->getDoctrine()->getManager()->remove($favorite);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array('message' => "Le favoris a bien été supprimé"));
    }
}
