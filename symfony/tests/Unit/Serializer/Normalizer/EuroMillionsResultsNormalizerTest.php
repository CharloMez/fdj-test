<?php
namespace App\Tests\Unit\Serializer\Normalizer;

use App\Controller\EuroMillionsController;
use App\Serializer\Normalizer\EuroMillionsResultsNormalizer;
use PHPUnit\Framework\TestCase;

class EuroMillionsResultsNormalizerTest extends TestCase
{
    public function testSupportNormalizationOk()
    {
        $normalizer = new EuroMillionsResultsNormalizer();
        $this->assertTrue($normalizer->supportsNormalization([], EuroMillionsController::DATA_FORMAT));
    }

    public function testSupportNormalizationKo()
    {
        $normalizer = new EuroMillionsResultsNormalizer();
        $this->assertFalse($normalizer->supportsNormalization([], 'something'));
    }

    public function testFullNormalizationOk()
    {
        $dataMock = [
            [
                'something' => 'must be deleted',
                'drawn_at' => 'some date',
                'results' => [
                    [
                        'type' => 'number',
                        'value' => 42,
                        'other key' => 'other value',
                    ],
                    [
                        'type' => 'number',
                        'value' => 4242,
                        'other key' => 'other value',
                    ],
                    [
                        'type' => 'special',
                        'value' => -42,
                        'other key' => 'other value',
                    ],
                    [
                        'type' => 'special',
                        'value' => -4242,
                        'other key' => 'other value',
                    ],
                    [
                        'type' => 'random type',
                    ],
                ],
                'addons' => [
                    [
                        'value' => 'addon value',
                        'other key' => 'other value',
                    ]
                ]
            ],
        ];

        $normalizer = new EuroMillionsResultsNormalizer();
        $result = $normalizer->normalize($dataMock, 'something');

        $this->assertSame(
            [
                'drawn_at' => 'some date',
                'numbers' => [
                    42,
                    4242
                ],
                'specials' => [
                    -42,
                    -4242
                ],
                'addons' => 'addon value',
            ],
            $result
        );
    }

    public function testMinimalNormalizationOk()
    {
        $normalizer = new EuroMillionsResultsNormalizer();
        $result = $normalizer->normalize([], 'something');

        $this->assertSame(
            [
                'drawn_at' => null,
                'numbers' => [],
                'specials' => [],
                'addons' => null,
            ],
            $result
        );
    }
}
