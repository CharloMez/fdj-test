<?php
namespace App\Controller;

use App\Service\FdjApiProxy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EuroMillionsController extends AbstractController
{
    public const DATA_FORMAT = 'euro-millions-results';

    public function __construct(
        private FdjApiProxy $fdjApiProxy,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/euro-millions/results', name: 'action')]
    public function resultsAction(): Response
    {
        $result = $this->fdjApiProxy->getEuroMillionsResults();

        return $this->render(
            'euro-millions/results.html.twig',
            $this->serializer->normalize($result, self::DATA_FORMAT)
        );
    }
}
