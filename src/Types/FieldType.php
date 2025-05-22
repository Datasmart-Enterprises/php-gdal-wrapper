<?php

namespace Datasmart\GDAL\Types;

enum FieldType
{
    case All;
    case Integer;
    case Integer64;
    case Real;
    case String;
    case Date;
    case Time;
    case DateTime;
    case Binary;
    case IntegerList;
    case Integer64List;
    case RealList;
    case StringList;
}
