<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditAdminType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP petition
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="admin_index",
     * )
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        return $this->render(
            'admin/index.html.twig'
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP user
     * @param \App\Repository\UserRepository            $userRepository User repository
     * @param int                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_user_edit",
     * )
     */
    public function edit_user(UserInterface $loggedUser, Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, User $user): Response
    {
        dump($loggedUser);
        $form = $this->createForm(UserEditAdminType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rol = $form->get('status')->getData();

            if (1 == $rol) {
                $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            } elseif (0 == $rol) {
                $userId = $loggedUser->getId();
                $petitionUser = $user->getId();
                if ($userId == $petitionUser) {
                    $this->addFlash('success', 'you cant deprive yourself');
                } else {
                    $user_roles = $user->getRoles();
                    $user->removeRole($user_roles);
                    //$user->setRoles(['ROLE_USER']);
                }
            }
            $userRepository->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * GrantAdmin action.
     *
     * @param UserRepository $repository
     *
     * @Route(
     *     "/{id}/grant",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="grant_admin",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function grantAdmin(Request $request, User $user, UserRepository $userRepository): Response
    {
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $userRepository->save($user);

        $this->addFlash('success', 'message.updated_successfully');

        return $this->redirectToRoute('user_index');
    }

    /**
     * DepriveAdmin action.
     *
     * @param UserRepository $repository
     *
     * @Route(
     *     "/{id}/deprive",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="deprive_admin",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function depriveAdmin(UserInterface $loggedUser, Request $request, User $user, UserRepository $userRepository): Response
    {
        $userId = $loggedUser->getId();

//        $roles_for_user = $user->getRoles();
//        $repository = $this->getDoctrine()->getRepository(User::class);
//        #$admins = $repository->findBy(['roles' => $roles_for_user]);
//        $admins = $userRepository->findBy(array('roles' => $roles_for_user));
//        #$admins = $repository->findAll();

        $petitionUser = $user->getId();

        if ($userId == $petitionUser) {
            $this->addFlash('success', 'you cant deprive yourself');
        } else {
            $user_roles = $user->getRoles();
            $user->removeRole($user_roles);
            //$user->setRoles(['ROLE_USER']);
            $userRepository->save($user);
            $this->addFlash('success', 'message.updated');
        }

        return $this->redirectToRoute('user_index');
    }
}
