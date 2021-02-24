<?php
namespace App\Serializer\Normalizer;

use App\Controller\EuroMillionsController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EuroMillionsResultsNormalizer implements NormalizerInterface
{
    private const TYPE_NUMBER = 'number';
    private const TYPE_SPECIAL = 'special';

    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'drawn_at' => $object[0]['drawn_at'] ?? null,
            'numbers' => $this->extractNumbers($object),
            'specials' => $this->extractSpecials($object),
            'addons' => $object[0]['addons'][0]['value'] ?? null,
        ];
    }

    private function extractNumbers($object): array
    {
        $numbers = [];
        foreach ($object[0]['results'] ?? [] as $result) {
            if (self::TYPE_NUMBER === $result['type']) {
                $numbers[] = $result['value'];
            }
        }

        return $numbers;
    }

    private function extractSpecials($object): array
    {
        $specials = [];
        foreach ($object[0]['results'] ?? [] as $result) {
            if (self::TYPE_SPECIAL === $result['type']) {
                $specials[] = $result['value'];
            }
        }

        return $specials;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return EuroMillionsController::DATA_FORMAT === $format;
    }
}
