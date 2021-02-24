<?php
namespace App\Controller;

use App\Service\FdjApiProxy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EuroMillionsController extends AbstractController
{
    public const DATA_FORMAT = 'euro-millions-results';

    public function __construct(
        private FdjApiProxy $fdjApiProxy,
        private NormalizerInterface $normalizer,
    ) {}

    #[Route(
        '/euro-millions/results',
        name: 'action',
        methods: ['GET']
    )]
    public function resultsAction(): Response
    {
        try {
            $result = $this->fdjApiProxy->getEuroMillionsResults();
        } catch (HttpException $e) {
            throw new BadRequestHttpException("An error occurred, please try again later.");
        }

        return $this->render(
            'euro-millions/results.html.twig',
            $this->normalizer->normalize($result, self::DATA_FORMAT)
        );
    }
}
