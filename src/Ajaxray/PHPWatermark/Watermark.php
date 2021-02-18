<?php
namespace Ajaxray\PHPWatermark;


/**
 * Class Watermark
 * @package Ajaxray\PHPWatermark
 */
class Watermark
{
    // Anchors to place
    const POSITION_TOP_LEFT = 'NorthWest';
    const POSITION_TOP = 'North';
    const POSITION_TOP_RIGHT = 'NorthEast';
    const POSITION_LEFT = 'West';
    const POSITION_CENTER = 'Center';
    const POSITION_RIGHT = 'East';
    const POSITION_BOTTOM_LEFT = 'SouthWest';
    const POSITION_BOTTOM = 'South';
    const POSITION_BOTTOM_RIGHT = 'SouthEast';

    const STYLE_IMG_DISSOLVE = 1;
    const STYLE_IMG_COLORLESS = 2;
    const STYLE_TEXT_BEVEL = 1;
    const STYLE_TEXT_DARK = 2;
    const STYLE_TEXT_LIGHT = 3;

    const PATTERN_MIME_PDF = '/^application\/(x\-)?pdf$/';

    private $options = [
        'position' => 'Center',
        'offsetX' => 0,
        'offsetY' => 0,
        'tiled' => false,
        'tileSize' => [100, 100],
        'font' => 'Arial',
        'fontSize' => 24,
        'opacity' => 0.3,
        'rotate' => 0,
        'style' => 1, // STYLE_IMG_DISSOLVE or STYLE_TEXT_BEVEL
    ];

    private $source;
    private $commander;
    private $debug = false;

    /**
     * Watermark constructor.
     * @param string $source Source
     */
    public function __construct($source)
    {
        $this->source = $source;
        $this->commander = $this->getCommandBuilder($source);
        return $this;
    }

    public function withText($nome, $cpf, $writeTo = null)
    {
        $destination = $writeTo ?: $this->source;
        $this->ensureWritable(($writeTo ? dirname($destination) : $destination));

        if($this->debug) {
            return $this->commander->getTextMarkCommand($nome, $cpf, $destination, $this->options, $this->options2);
        } else {
            $output = $returnCode = null;
            exec($this->commander->getTextMarkCommand($nome, $cpf, $destination, $this->options, $this->options2), $output, $returnCode);
            return (empty($output) && $returnCode === 0);
        }
    }

    /**
     * Factory for choosing CommandBuilder
     * @param $sourcePath
     * @return CommandBuilders\CommandBuilders\PDFCommandBuilder
     */
    protected function getCommandBuilder($sourcePath)
    {
        $this->ensureExists($this->source);
        $mimeType = mime_content_type($sourcePath);

        if (preg_match(self::PATTERN_MIME_PDF, $mimeType)) {
            return new CommandBuilders\PDFCommandBuilder($sourcePath);
        } else {
            throw new \InvalidArgumentException("O tipo de arquivo de origem $mimeType não é compatível.");
        }
    }

    /**
     * @param string $position  One of Watermark::POSITION_* constants
     * @return Watermark
     */
    public function setPosition($position)
    {
        if(in_array($position, $this->supportedPositionList())) {
            $this->options['position'] = $position;
        } else {
            throw new \InvalidArgumentException("A posição $position não é suportada! Use  Watermark::POSITION_ *.");
        }
        return $this;
    }

    /**
     * @param int $offsetX
     * @param int $offsetY
     * @return Watermark
     */
    public function setOffsetName($offsetX, $offsetY)
    {
        $this->options['offsetX'] = intval($offsetX);
        $this->options['offsetY'] = intval($offsetY);
        return $this;
    }

    /**
     * @param int $offsetX
     * @param int $offsetY
     * @return Watermark
     */
    public function setOffsetCpf($offsetX2, $offsetY2)
    {
        $this->options2['offsetX2'] = intval($offsetX2);
        $this->options2['offsetY2'] = intval($offsetY2);
        return $this;
    }

    /**
     * Font name. Should be one of the list displayed by `convert -list font` command
     * @param string $font
     * @return Watermark
     */
    public function setFont($font)
    {
        $this->options['font'] = $font;
        return $this;
    }

    /**
     * @param int $fontSize
     * @return Watermark
     */
    public function setFontSize($fontSize)
    {
        $this->options['fontSize'] = intval($fontSize);
        return $this;
    }


    /**
     * @param float $opacity Between .1 (very transparent) to .9 (almost opaque).
     * @return Watermark
     */
    public function setOpacity($opacity)
    {
        $this->options['opacity'] = floatval($opacity);
        if($this->options['opacity'] < 0 || $this->options['opacity'] > 1) {
            throw new \InvalidArgumentException('A opacidade deve ser entre 0 e 1!');
        }
        return $this;
    }

    /**
     * @param int $rotate Degree of rotation
     * @return Watermark
     */
    public function setRotate($rotate)
    {
        $this->options['rotate'] = abs(intval($rotate));
        return $this;
    }

    /**
     * @param bool $debug
     * @return Watermark
     */
    public function setDebug($debug = true)
    {
        $this->debug = boolval($debug);
        return $this;
    }

    final public function supportedPositionList()
    {
        return [
            self::POSITION_TOP_LEFT,
            self::POSITION_TOP,
            self::POSITION_TOP_RIGHT,
            self::POSITION_RIGHT,
            self::POSITION_CENTER,
            self::POSITION_LEFT,
            self::POSITION_BOTTOM_LEFT,
            self::POSITION_BOTTOM,
            self::POSITION_BOTTOM_RIGHT,
        ];
    }

    private function ensureExists($filePath)
    {
        if (! file_exists($filePath)) {
            $message = "O arquivo especificado $filePath não foi encontrado!";
            throw new \InvalidArgumentException($message);
        }
    }

    private function ensureWritable($dirPath)
    {
        if (!is_writable($dirPath)) {
            $message = "O destino especificado $dirPath não é gravável!";
            throw new \InvalidArgumentException($message);
        }
    }
}
