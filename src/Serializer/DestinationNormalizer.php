<?php

namespace App\Serializer;

use App\Entity\Destination;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Twig\Extension\UploaderExtensionRuntime;

class DestinationNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
        private UploaderExtensionRuntime $uploaderHelper,
        private UrlHelper $urlHelper
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data['destinationImage'] = $this->urlHelper->getAbsoluteUrl($this->uploaderHelper->asset($object, 'imageFile'));
        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Destination;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Destination::class => true,
        ];
    }
}
