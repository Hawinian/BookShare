<?php
/**
 * Rental controller.
 */

namespace App\Controller;

use App\Entity\Giveback;
use App\Entity\Rental;
use App\Form\GivebackType;
use App\Repository\GivebackRepository;
use App\Repository\RentalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RentalController.
 *
 * @Route("/rental")
 */
class RentalController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP rental
     * @param \App\Repository\RentalRepository          $rentalRepository Rental repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator        Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="rental_index",
     * )
     */
    public function index(Request $request, RentalRepository $rentalRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $rentalRepository->queryAll(),
            $request->query->getInt('page', 1),
            RentalRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'rental/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Return action.
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
     *     "/{id}/return",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="rental_return",
     * )
     */
    public function return(Request $request, Rental $rental, GivebackRepository $givebackRepository, RentalRepository $rentalRepository): Response
    {
        $rentalId = $rental->getId();
        $repository = $this->getDoctrine()->getRepository(Giveback::class);
        $existingRental = $repository->findOneBy(['rental' => $rentalId]);

        if ($existingRental) {
            $this->addFlash('alert', 'you cannot return this book');

            return $this->redirectToRoute('rental_index');
        } else {
            $giveback = new Giveback();
            $form = $this->createForm(GivebackType::class, $giveback);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $giveback->setDate(new \DateTime());
                $giveback->setRental($rental);
                $givebackRepository->save($giveback);

                $this->addFlash('success', 'message_created_successfully');

                return $this->redirectToRoute('rental_index');
            }
        }

        return $this->render(
            'rental/return.html.twig',
            ['rental' => $rental,
                'form' => $form->createView(), ]
        );
    }
}
