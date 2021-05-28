<?php
/**
 * Giveback controller.
 */

namespace App\Controller;

use App\Entity\Giveback;
use App\Entity\Rental;
use App\Repository\GivebackRepository;
use App\Repository\RentalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class GivebackController.
 *
 * @Route("/giveback")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class GivebackController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP giveback
     * @param \App\Repository\GivebackRepository        $givebackRepository Giveback repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="giveback_index",
     * )
     */
    public function index(Request $request, GivebackRepository $givebackRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $givebackRepository->queryAll(),
            $request->query->getInt('page', 1),
            GivebackRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'giveback/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP rental
     * @param \App\Repository\RentalRepository          $rentalRepository Rental repository
     * @param int                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/accept",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="giveback_accept",
     * )
     */
    public function accept(Request $request, Giveback $giveback, RentalRepository $rentalRepository, GivebackRepository $givebackRepository, string $id): Response
    {
        $form = $this->createForm(FormType::class, $giveback, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rental = $giveback->getRental();
            $givebackRepository->delete($giveback);
            $rentalRepository->delete($rental);

            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('giveback_index');
        }

        return $this->render(
            'giveback/accept.html.twig',
            [
                'form' => $form->createView(),
                'giveback' => $giveback,
            ]
        );
    }

    /**
     * Reject action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP rental
     * @param \App\Repository\RentalRepository          $rentalRepository Rental repository
     * @param int                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/reject",
     *     methods={"GET","DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="giveback_reject",
     * )
     */
    public function reject(Request $request, Giveback $giveback, GivebackRepository $givebackRepository, string $id): Response
    {
        $form = $this->createForm(FormType::class, $giveback, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $givebackRepository->delete($giveback);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('petition_index');
        }

        return $this->render(
            'giveback/reject.html.twig',
            [
                'form' => $form->createView(),
                'giveback' => $giveback,
            ]
        );
    }
}
