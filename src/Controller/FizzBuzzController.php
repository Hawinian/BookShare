<?php

namespace App\Controller;

use App\Service\FizzBuzzService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HelloWorldController.
 *
 * @Route("/fizz-buzz")
 */
class FizzBuzzController extends AbstractController
{
    /**
     * @var \App\Service\FizzBuzzService
     */
    private $fizzBuzzService;

    /**
     * CategoryController constructor.
     *
     * @param \App\Service\FizzBuzzService $fizzBuzzService FizzBuzz service
     */
    public function __construct(FizzBuzzService $fizzBuzzService)
    {
        $this->fizzBuzzService = $fizzBuzzService;
    }

    /**
     * Index action.
     *
     * @param int $number User input
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{number}",
     *     methods={"GET"},
     *     name="fizz-buzz_index",
     *     defaults={"number":"1"},
     * )
     */
    public function index(int $number): Response
    {
        $result = $this->fizzBuzzService->execute();

        return $this->render(
            'fizz-buzz/index.html.twig',
            ['text' => $result[$number - 1], 'number' => $number]
        );
    }
}
