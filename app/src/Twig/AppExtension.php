<?php
namespace App\Twig;


use App\Service\UploadHelper;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    private $_container;


    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath'])
        ];
    }

    public function getUploadedAssetPath(string $path): string
    {
        return $this->_container
            ->get(UploadHelper::class)
            ->getUploadPath($path, true);
    }
    public static function getSubscribedServices()
    {
        return [
            UploadHelper::class,
        ];
    }
}