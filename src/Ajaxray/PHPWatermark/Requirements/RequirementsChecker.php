<?php
namespace Ajaxray\PHPWatermark\Requirements;

class RequirementsChecker
{
    public function checkImagemagickInstallation()
    {
        exec("convert -version", $out, $rcode);

        if ($rcode) {
            throw new \BadFunctionCallException("ImageMagick não encontrado neste sistema.");
        }
        return true;
    }
}