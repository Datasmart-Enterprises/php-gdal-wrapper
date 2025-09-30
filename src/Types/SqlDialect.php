<?php

namespace Datasmart\GDAL\Types;

enum SqlDialect
{
    case SQLite;
    case INDIRECT_SQLITE;
    case OGRSQL;
}
