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
use App\Service\RentalService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RentalController.
 *
 * @Route("/rental")
 *
 * @IsGranted("ROLE_USER")
 */
class RentalController extends AbstractController
{
    /**
     * Rental service.
     *
     * @var \App\Service\RentalService
     */
    private $rentalService;

    /**
     * RentalController constructor.
     *
     * @param \App\Service\RentalService $rentalService Rental service
     */
    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

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
     *     "",
     *     methods={"GET"},
     *     name="rental_index",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, RentalRepository $rentalRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->rentalService->createPaginatedListAuthor($page, $this->getUser());
//        $pagination = $paginator->paginate(
//            $rentalRepository->queryByAuthor($this->getUser()),
//            $request->query->getInt('page', 1),
//            RentalRepository::PAGINATOR_ITEMS_PER_PAGE
//        );

        return $this->render(
            'rental/index.html.twig',
            ['pagination' => $pagination]
        );
    }

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
     *     "/late_books",
     *     methods={"GET"},
     *     name="late_books",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function late_books(Request $request, RentalRepository $rentalRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->rentalService->createPaginatedListLateBooks($page);

        return $this->render(
            'rental/late-books.html.twig',
            ['pagination' => $pagination]
        );
    }

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
     *     "/in_time_books",
     *     methods={"GET"},
     *     name="in_time_books",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function in_time_books(Request $request, RentalRepository $rentalRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->rentalService->createPaginatedListInTimeBooks($page);

        return $this->render(
            'rental/in-time-books.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Return action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP rental
     * @param \App\Repository\RentalRepository          $rentalRepository Rental repository
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
     *
     * @IsGranted(
     *     "RETURN",
     *     subject="rental",
     * )
     */
    public function return(Request $request, Rental $rental, GivebackRepository $givebackRepository, RentalRepository $rentalRepository): Response
    {
        $existingRental = $rental->getGiveback();

        if ($existingRental) {
            $this->addFlash('warning', 'message_book_already_sent_to_return');

            return $this->redirectToRoute('rental_index');
        }

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

        return $this->render(
            'rental/return.html.twig',
            ['rental' => $rental,
                'form' => $form->createView(), ]
        );
    }
}
