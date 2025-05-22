<?php

namespace Datasmart\GDAL\Types;

enum GeometryType
{
    case NONE;
    case GEOMETRY;
    case POINT;
    case LINESTRING;
    case POLYGON;
    case GEOMETRYCOLLECTION;
    case MULTIPOINT;
    case MULTIPOLYGON;
    case MULTILINESTRING;
    case CIRCULARSTRING;
    case COMPOUNDCURVE;
    case CURVEPOLYGON;
    case MULTICURVE;
    case MULTISURFACE;
}
