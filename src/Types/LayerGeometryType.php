<?php

namespace Datasmart\GDAL\Types;

/**
 * Define the geometry type for the created layer. One of NONE, GEOMETRY, POINT, LINESTRING, POLYGON, GEOMETRYCOLLECTION, MULTIPOINT, MULTIPOLYGON, MULTILINESTRING, CIRCULARSTRING, COMPOUNDCURVE, CURVEPOLYGON, MULTICURVE, and MULTISURFACE non-linear geometry types. Add Z, M, or ZM to the type name to specify coordinates with elevation, measure, or elevation and measure. PROMOTE_TO_MULTI can be used to automatically promote layers that mix polygon or multipolygons to multipolygons, and layers that mix linestrings or multilinestrings to multilinestrings. Can be useful when converting shapefiles to PostGIS and other target drivers that implement strict checks for geometry types. CONVERT_TO_LINEAR can be used to to convert non-linear geometry types into linear geometry types by approximating them, and CONVERT_TO_CURVE to promote a non-linear type to its generalized curve type (POLYGON to CURVEPOLYGON, MULTIPOLYGON to MULTISURFACE, LINESTRING to COMPOUNDCURVE, MULTILINESTRING to MULTICURVE). Starting with version 2.1 the type can be defined as measured ("25D" remains as an alias for single "Z"). Some forced geometry conversions may result in invalid geometries, for example when forcing conversion of multi-part multipolygons with -nlt POLYGON, the resulting polygon will break the Simple Features rules.
 *
 * Starting with GDAL 3.0.5, -nlt CONVERT_TO_LINEAR and -nlt PROMOTE_TO_MULTI can be used simultaneously.
 */
class LayerGeometryType
{
    private const string ELEVATION_MEASURE_INDICATOR = 'ZM';
    private const string ELEVATION_INDICATOR = 'Z';
    private const string MEASURE_INDICATOR = 'M';
    private const string SUBCOMMAND_CONVERT_TO_LINEAR = 'CONVERT_TO_LINEAR';
    private const string SUBCOMMAND_CONVERT_TO_CURVE = 'CONVERT_TO_CURVE';
    private const string SUBCOMMAND_CONVERT_TO_MULTI = 'CONVERT_TO_MULTI';

    private function __construct(
        private GeometryType $geometryType,
        private bool $elevation = false,
        private bool $measure = false,
        private string $coordinates = '',
        private bool $convertToLinear = false,
        private bool $convertToCurve = false,
        private bool $convertToMulti = false,
    )
    {
    }

    public function getGeometryTypeName(bool $escape = false): string
    {
        if ($this->hasElevation() === true && $this->hasMeasure() === true) {
            return $this->geometryType->name . self::ELEVATION_MEASURE_INDICATOR;
        }
        if ($this->hasElevation() === true) {
            return $this->geometryType->name . self::ELEVATION_INDICATOR;
        }
        if ($this->hasMeasure() === true) {
            return $this->geometryType->name . self::MEASURE_INDICATOR;
        }
        if ($escape === true) {
            return escapeshellarg($this->geometryType->name);
        }
        return $this->geometryType->name;
    }

    public function getString()
    {
        $string = '';

        if ($this->convertToLinear === true) {
            $string .= '-nlt ' . escapeshellarg(self::SUBCOMMAND_CONVERT_TO_LINEAR) . ' ';
        }
        if ($this->convertToCurve === true) {
            $string .= '-nlt ' . escapeshellarg(self::SUBCOMMAND_CONVERT_TO_CURVE) . ' ';
        }
        if ($this->convertToMulti === true) {
            $string .= '-nlt ' . escapeshellarg(self::SUBCOMMAND_CONVERT_TO_MULTI) . ' ';
        }
        if (empty($string)) {
            $coordinates = !empty($this->coordinates) ? escapeshellarg($this->coordinates) : '';
            $string = "-nlt {$this->getGeometryTypeName(true)} . $coordinates";
        }
        return $string;
    }

    public function setGeometryType(GeometryType $geometryType): void
    {
        $this->geometryType = $geometryType;
    }

    public function hasElevation(): bool
    {
        return $this->elevation;
    }

    public function setElevation(bool $elevation): void
    {
        $this->elevation = $elevation;
    }

    public function hasMeasure(): bool
    {
        return $this->measure;
    }

    public function setMeasure(bool $measure): void
    {
        $this->measure = $measure;
    }

    public function setConvertToLinear(bool $convertToLinear): void
    {
        $this->convertToLinear = $convertToLinear;
    }

    public function setConvertToCurve(bool $convertToCurve): void
    {
        $this->convertToCurve = $convertToCurve;
    }

    public function setConvertToMulti(bool $convertToMulti): void
    {
        $this->convertToMulti = $convertToMulti;
    }

    public function setCoordinates(string $coordinates): void
    {
        $this->coordinates = $coordinates;
    }

    public static function createNewLayerGeometryType(
        GeometryType $geometryType,
        bool $elevation = false,
        bool $measure = false,
        string $coordinates = '',
        bool $convertToLinear = false,
        bool $convertToCurve = false,
        bool $convertToMulti = false,
    )
    {
        return new self($geometryType, $elevation, $measure, $coordinates, $convertToLinear, $convertToCurve, $convertToMulti);
    }
}