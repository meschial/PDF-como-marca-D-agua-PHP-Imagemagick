<?php
namespace Ajaxray\PHPWatermark\CommandBuilders;

abstract class AbstractCommandBuilder
{
    protected $options;
    protected $options2;

    /**
     * @var string Source file path
     */
    protected $source;

    /**
     * AbstractCommandBuilder constructor.
     * @param string $source The source file to watermark on
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     *
     * @param string $name, $cpf The text content to watermark with
     * @param string $output The watermarked output file
     * @param array $options, $options2
     * @return string
     */
    abstract public function getTextMarkCommand($name, $cpf, $output, array $options,  array $options2);

    /**
     * @return string
     */
    protected function getSource()
    {
        return escapeshellarg($this->source);
    }

    /**
     * @param $output
     * @param array $options, $options2
     * @return array
     */
    protected function prepareContext($output, array $options,  array $options2)
    {
        $this->options = $options;
        $this->options2 = $options2;
        return array($this->getSource(), escapeshellarg($output));
    }

    protected function getAnchor()
    {
        return 'gravity '. $this->options['position'];
    }

    /**
     * @return array
     */
    protected function getOffsetName()
    {
        return [$this->options['offsetX'], $this->options['offsetY']];
    }

    /**
     * @return array
     */
    protected function getOffsetCpf()
    {
        return [$this->options2['offsetX2'], $this->options2['offsetY2']];
    }

    /**
     * @return string
     */
    protected function getFont()
    {
        return '-pointsize '.intval($this->options['fontSize']).
            ' -font '.escapeshellarg($this->options['font']);
    }

    protected function getDuelNameOffset()
    {
        $offset = $this->getOffsetName();
        return [
            "{$offset[0]},{$offset[1]}",
            ($offset[0] + 1) .','. ($offset[1] + 1),
        ];
    }

    protected function getDuelCpfOffset()
    {
        $offset2 = $this->getOffsetCpf();
        return [
            "{$offset2[0]},{$offset2[1]}",
            ($offset2[0] + 1) .','. ($offset2[1] + 1),
        ];
    }

    /**
     * @return float
     */
    protected function getOpacity()
    {
        return $this->options['opacity'];
    }
}
