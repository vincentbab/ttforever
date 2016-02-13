<?php

namespace Mping\CoreBundle\Templating\Helper;

use Symfony\Bundle\AsseticBundle\Templating\StaticAsseticHelper;
use Assetic\Factory\AssetFactory;
use Symfony\Component\Templating\Helper\CoreAssetsHelper;
use Symfony\Component\Asset\Packages;

/**
 * AsseticHelper
 */
class AsseticHelper extends StaticAsseticHelper
{
    private $packages;
    private $assetsHelper;

    /**
     * Constructor.
     *
     * @param Packages|CoreAssetsHelper $packages The assets packages
     * @param AssetFactory              $factory  The asset factory
     */
    public function __construct($packages, AssetFactory $factory)
    {
        if ($packages instanceof Packages) {
            $this->packages = $packages;
        } elseif ($packages instanceof CoreAssetsHelper) {
            $this->assetsHelper = $packages;
        }

        parent::__construct($packages, $factory);
    }

    /**
     * {@inheritdoc}
     */
    public function javascripts($inputs = array(), $filters = array(), array $options = array())
    {
        if ($this->factory->isDebug()) {
            return $this->getUrls($inputs);
        }

        return parent::javascripts($inputs, $filters, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function stylesheets($inputs = array(), $filters = array(), array $options = array())
    {
        if ($this->factory->isDebug()) {
            return $this->getUrls($inputs);
        }

        return parent::stylesheets($inputs, $filters, $options);
    }

    /**
     * Retourne les url des assets spécifiés
     *
     * @param array $inputs
     *
     * @return array
     */
    protected function getUrls($inputs)
    {
        $urls = array();

        foreach ($inputs as $asset) {
            if (null === $this->packages) {
                $urls[] = $this->assetsHelper->getUrl($asset);
            } else {
                $urls[] = $this->packages->getPackage(null)->getUrl($asset);
            }
        }

        return $urls;
    }
}
