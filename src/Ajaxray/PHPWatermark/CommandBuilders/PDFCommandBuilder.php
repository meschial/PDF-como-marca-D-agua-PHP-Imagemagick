<?php

namespace Ajaxray\PHPWatermark\CommandBuilders;


class PDFCommandBuilder extends AbstractCommandBuilder
{
    /**
     *
     * @param string $nome, $cpf The text content to watermark with
     * @param string $output The watermarked output file
     * @param array $options, $options2
     * @return string
     */
    public function getTextMarkCommand($nome, $cpf, $output, array $options, array $options2)
    {
        list($source, $destination) = $this->prepareContext($output, $options, $options2);
        $nome = escapeshellarg($nome);
        $cpf = escapeshellarg($cpf);

        $anchor = $this->getAnchor();
        $rotate = $this->getRotate();
        $font = $this->getFont();
        list($light, $dark) = $this->getDuelTextColor();
        list($offsetLight, $offsetDark) = $this->getDuelNameOffset();
        list($offsetLight2, $offsetDark2) = $this->getDuelCpfOffset();

        $pdf = "convert $source -$anchor -quality 100 -density 100 $font -$light -annotate {$rotate}{$offsetLight} $nome -$dark -annotate {$rotate}{$offsetDark} $nome -annotate {$rotate}{$offsetDark2} $cpf  $destination";

        return $pdf;
    }

    protected function getDuelNameOffset()
    {
        $offset = $this->getOffsetName();
        return [
            "+{$offset[0]}+{$offset[1]}",
            '+'.($offset[0] + 1) .'+'. ($offset[1] + 1),
        ];
    }

    protected function getDuelCpfOffset()
    {
        $offset2 = $this->getOffsetCpf();
        return [
            "+{$offset2[0]}+{$offset2[1]}",
            '+'.($offset2[0] + 1) .'+'. ($offset2[1] + 1),
        ];
    }

    protected function getRotate()
    {
        return empty($this->options['rotate']) ? '' : "{$this->options['rotate']}x{$this->options['rotate']}";
    }

    protected function getDuelTextColor()
    {
        return [
            "fill \"rgba(255,255,255,{$this->getOpacity()})\"",
            "fill \"rgba(0,0,0,{$this->getOpacity()})\"",
        ];
    }
}
