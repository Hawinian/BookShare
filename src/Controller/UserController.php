<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\EditDataType;
use App\Form\EditPasswordType;
use App\Form\UserEditAdminType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Service\UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP user
     * @param \App\Repository\UserRepository            $userRepository User repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="user_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->userService->createPaginatedList($page);

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
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
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="user_register",
     * )
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $requestRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('book_index');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setStatus(100);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'user/register.html.twig',
            [
                'form' => $form->createView(),
            ]
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
     *     "/{id}/edit-data",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="edit_data",
     * )
     *
     * @IsGranted(
     *     "USER",
     *     subject="user",
     * )
     */
    public function edit_data(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, User $user): Response
    {
        $form = $this->createForm(EditDataType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'user/edit-data.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
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
     *     "/{id}/edit-password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="edit_password",
     * )
     * @IsGranted(
     *     "USER",
     *     subject="user",
     * )
     */
    public function edit_password(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, User $user): Response
    {
        $form = $this->createForm(EditPasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $userRepository->upgradePassword($user, $password);

            $userRepository->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'user/edit-password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param UserInterface                             $loggedUser
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP user
     * @param \App\Repository\UserRepository            $userRepository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/admin-edit-data",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_edit_data",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin_edit_data(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, User $user): Response
    {
        $form = $this->createForm(UserEditAdminType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userId = $this->getUser()->getId();
            $petitionUser = $user->getId();

            if ($petitionUser == $userId) {
                $this->addFlash('warning', 'you cant deprive yourself');

                return $this->redirectToRoute('user_index');
            }

            $rol = $form->get('roles')->getData();
            if (in_array('ROLE_ADMIN', $rol)) {
                $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            }

            $userRepository->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/admin-edit-data.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
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
     *     "/{id}/admin-edit-password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_edit_password",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin_edit_password(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, User $user): Response
    {
        $form = $this->createForm(EditPasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $userRepository->upgradePassword($user, $password);

            $userRepository->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/admin-edit-password.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\User                          $user       User entity
     * @param \App\Repository\UserRepository            $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_delete",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($user);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP petition
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/admin",
     *     methods={"GET"},
     *     name="admin_index",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin_index(Request $request, PaginatorInterface $paginator): Response
    {
        return $this->render(
            'user/admin-index.html.twig'
        );
    }
}
