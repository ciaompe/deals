<?php

namespace Lucids\Lib\Twig\Extenstions;

use Slim\Views\TwigExtension;
use Slim\Slim;

class ShowAd extends TwigExtension{

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('showAd', array($this, 'showAd')),
            new \Twig_SimpleFunction('urlFor', array($this, 'urlFor')),
            new \Twig_SimpleFunction('baseUrl', array($this, 'base')),
            new \Twig_SimpleFunction('siteUrl', array($this, 'site')),
            new \Twig_SimpleFunction('currentUrl', array($this, 'currentUrl')),
        );
    }

    public function showAd($width, $height, $baseUrl, $imageUrl) {

        $ad = $this->getAd($width, $height);

        $app = Slim::getInstance();
        $token = hash('sha256', $app->request->getIp().'adoHukapn$^&*ponnaya');

        if ($ad && $ad->adunit != "") {

            if ($ad->adunit->type == "image") {
                return '<a href="'.$app->urlFor('advertise', ['id' => $ad->adunit->id]).'?token='.$token.'" target="_blank"><img class="lazyload" data-src="'.$ad->adunit->getImage($baseUrl, $imageUrl, true).'"></a>';
            }
            if ($ad->adunit->type == "code") {
                return $ad->adunit->getCode();
            }
        }

    }

    private function getAd($width, $height) {
		return \Lucids\Models\Advertise\Adsize::where('width', $width)->where('height', $height)->with('adunit')->first();
	}

}