<?php

namespace Modules\Media\Services;

use Exception;
use Intervention\Image\ImageManager;
use Log;

class ThumbnailService
{

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var string
     */
    protected $imagePath;

    /**
     * @var float
     */
    protected $thumbRate;

    /**
     * @var int
     */
    protected $thumbWidth;

    /**
     * @var int
     */
    protected $thumbHeight;

    /**
     * @var string
     */
    protected $destinationPath;

    /**
     * @var string
     */
    protected $xCoordinate;

    /**
     * @var string
     */
    protected $yCoordinate;

    /**
     * @var string
     */
    protected $fitPosition;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var UploadsManager
     */
    protected $uploadManager;

    /**
     * ThumbnailService constructor.
     * @param UploadsManager $uploadManager
     * @param ImageManager $imageManager
     */
    public function __construct(UploadsManager $uploadManager, ImageManager $imageManager)
    {
        $this->thumbRate = 0.75;
        $this->xCoordinate = null;
        $this->yCoordinate = null;
        $this->fitPosition = 'center';

        $driver = 'gd';
        if (extension_loaded('imagick')) {
            $driver = 'imagick';
        }

        $this->imageManager = $imageManager->configure(['driver' => $driver]);
        $this->uploadManager = $uploadManager;
    }

    /**
     * @param string $imagePath
     * @return ThumbnailService
     */
    public function setImage($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return string $imagePath
     */
    public function getImage()
    {
        return $this->imagePath;
    }

    /**
     * @param double $rate
     * @return ThumbnailService
     */
    public function setRate($rate)
    {
        $this->thumbRate = $rate;

        return $this;
    }

    /**
     * @return double $thumbRate
     */
    public function getRate()
    {
        return $this->thumbRate;
    }

    /**
     * @param $width
     * @param null $height
     * @return ThumbnailService
     */
    public function setSize($width, $height = null)
    {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;

        if (empty($height)) {
            $this->thumbHeight = ($this->thumbWidth * $this->thumbRate);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSize()
    {
        return [$this->thumbWidth, $this->thumbHeight];
    }

    /**
     * @param string $destinationPath
     * @return ThumbnailService
     */
    public function setDestinationPath($destinationPath)
    {
        $this->destinationPath = $destinationPath;

        return $this;
    }

    /**
     * @return string $destinationPath
     */
    public function getDestinationPath()
    {
        return $this->destinationPath;
    }

    /**
     * @param integer $xCoord
     * @param integer $yCoord
     * @return ThumbnailService
     */
    public function setCoordinates($xCoordination, $yCoordination)
    {
        $this->xCoordinate = $xCoordination;
        $this->yCoordinate = $yCoordination;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoordinates()
    {
        return [$this->xCoordinate, $this->yCoordinate];
    }

    /**
     * @param string $position
     * @return ThumbnailService
     */
    public function setFitPosition($position)
    {
        $this->fitPosition = $position;

        return $this;
    }

    /**
     * @return string $fitPosition
     */
    public function getFitPosition()
    {
        return $this->fitPosition;
    }

    /**
     * @param string $fileName
     * @return ThumbnailService
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string $fileName
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $type
     * @return bool|string
     */
    public function save($type = 'fit')
    {
        $fileName = pathinfo($this->imagePath, PATHINFO_BASENAME);

        if ($this->fileName) {
            $fileName = $this->fileName;
        }

        $destinationPath = sprintf('%s/%s', trim($this->destinationPath, '/'), $fileName);

        $thumbImage = $this->imageManager->make($this->imagePath);

        switch ($type) {
            case 'resize':
                $thumbImage->resize($this->thumbWidth, $this->thumbHeight);
                break;
            case 'crop':
                $thumbImage->crop($this->thumbWidth, $this->thumbHeight, $this->xCoordinate, $this->yCoordinate);
                break;
            case 'fit':
                $thumbImage->fit($this->thumbWidth, $this->thumbHeight, null, $this->fitPosition);
        }

        try {
            $this->uploadManager->saveFile($destinationPath, $thumbImage->stream()->__toString());
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }

        return $destinationPath;
    }
}
