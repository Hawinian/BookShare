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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GivebackController.
 *
 * @Route("/giveback")
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
     *     "/",
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
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="giveback_accept",
     * )
     */
    public function accept(Request $request, Giveback $giveback, RentalRepository $rentalRepository, GivebackRepository $givebackRepository, string $id): Response
    {
        $rental = $giveback->getRental();
        $givebackRepository->delete($giveback);
        $rentalRepository->delete($rental);

        $this->addFlash('success', 'message_deleted_successfully');

        return $this->redirectToRoute('giveback_index');
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
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="giveback_reject",
     * )
     */
    public function reject(Request $request, Giveback $giveback, GivebackRepository $givebackRepository, string $id): Response
    {
        //$givebackRepository->delete($giveback);

        $this->addFlash('success', 'message_rejected_successfully');

        return $this->redirectToRoute('giveback_index');
    }
}
