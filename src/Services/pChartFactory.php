<?php
namespace CpChart\Services;

use CpChart\Classes\pData;
use CpChart\Classes\pImage;

// The GD extension is mandatory
if (!extension_loaded('gd') && !extension_loaded('gd2')) {
    throw new \Exception("GD extension must be loaded. \r\n");
}

// add constants required by the library classes
require_once __DIR__.'/../Resources/data/constants.php';

/**
 * A simple service class utilizing the Factory design pattern. It has three 
 * class specific methods, as well as a generic loader for the chart classes.
 *
 * @author szymach
 */
class pChartFactory
{
    private $namespace = 'CpChart\Classes\\';
    
    /**
     * Load a new chart class (bar, pie etc.). Some classes require instances of
     * pImage and pData classes passed into their constructor. These classes are: 
     * pBubble, pPie, pScatter, pStock, pSurface and pIndicator. Otherwise the 
     * pChartObject and pDataObject parameters are redundant.
     * 
     * @param string $chartType - type of the chart to be loaded (for example 'pie', not 'pPie')
     * @param \CpChart\Classes\pImage $pChartObject
     * @param \CpChart\Classes\pData $pDataObject
     * @return \CpChart\Classes\$chartName
     */
    public function newChart(
        $chartType,
        pImage $pChartObject = null, 
        pData $pDataObject = null
    ) {
        $className = $this->namespace.'p'.ucfirst($chartType);
        return new $className($pChartObject, $pDataObject);
    }
    
    /**
     * Creates a new pData class with an option to pass the data to form a serie.
     * 
     * @param array $points - points to be added to serie
     * @param string $serieName - name of the serie
     * @return \CpChart\Classes\pData
     */
    public function newData(array $points = array(), $serieName = "Serie1")
    {
        $className = $this->namespace.'pData';
        $data = new $className(); 
        if (count($points) > 0) {
            $data->addPoints($points, $serieName);
        }
        return $data;
    }
    
    /**
     * Create a new pImage class. It requires the size of axes to be properly
     * constructed.
     * 
     * @param integer $XSize - length of the X axis
     * @param integer $YSize - length of the Y axis
     * @param \CpChart\Classes\pData $DataSet - pData class populated with points
     * @param boolean $TransparentBackground
     * @return \CpChart\Services\pImage
     */
    public function newImage(
        $XSize,
        $YSize,
        \CpChart\Classes\pData $DataSet = null,
        $TransparentBackground = false
    ) {
        $className = $this->namespace.'pImage';
        return new $className(
            $XSize,
            $YSize,
            $DataSet,
            $TransparentBackground
        );
    }
    
    /**
     * Create one of the pBarcode classes. Only the number is required (39 or 128),
     * the class name is contructed on the fly. Passing the constructor's parameters
     * is also available, but not mandatory.
     * 
     * @param string $number - number identifing the pBarcode class ("39" or "128")
     * @param string $BasePath - optional path for the file containing the class data
     * @param boolean $EnableMOD43
     * @return \CpChart\Classes\pBarcode(39|128)
     * @throws \Exception
     */
    public function getBarcode($number, $BasePath = "", $EnableMOD43 = false)
    {
        if ($number != "39" && $number != "128") {
            throw new \Exception(
                'The barcode class for the provided number does not exist!'
            );
        }
        $className = $this->namespace."pBarcode".$number;
        return new $className($BasePath, $EnableMOD43);
    }
}
