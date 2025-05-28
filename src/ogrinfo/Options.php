<?php

/**
 * This file is part of the GDAL package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8.3
 *
 * @license GPL License
 */

namespace Datasmart\GDAL\ogrinfo;

use Datasmart\GDAL\Types\SpatialQueryExtent;
use Datasmart\GDAL\Types\SqlDialect;
use Datasmart\GDAL\Types\VectorFormat;

/**
 * @author Jonathan Beliën <jbe@geo6.be>
 */
class Options
{
    public const string GEOMETRY_NO = 'NO';
    public const string GEOMETRY_YES = 'YES';
    public const string GEOMETRY_SUMMARY = 'SUMMARY';
    public const string GEOMETRY_WELL_KNOWN_TEXT = 'WKT';
    public const string GEOMETRY_ISO_WELL_KNOWN_TEXT = 'ISO_WKT';

    public const array VALID_GEOMETRY_DUMP_TYPES = [
        self::GEOMETRY_NO,
        self::GEOMETRY_YES,
        self::GEOMETRY_SUMMARY,
        self::GEOMETRY_WELL_KNOWN_TEXT,
        self::GEOMETRY_ISO_WELL_KNOWN_TEXT
    ];

    public const string WKT_FORMAT_WKT1 = 'WKT1';
    public const string WKT_FORMAT_WKT2 = 'WKT2';
    public const string WKT_FORMAT_WKT2_2015 = 'WKT2_2015';
    public const string WKT_FORMAT_WKT2_2018 = 'WKT2_2018';

    public const array VALID_WKT_FORMATS = [
        self::WKT_FORMAT_WKT1,
        self::WKT_FORMAT_WKT2,
        self::WKT_FORMAT_WKT2_2015,
        self::WKT_FORMAT_WKT2_2018
    ];

    /** Show help message and exit */
    public bool $help = false;
    /** Gives a brief usage message for the generic GDAL commandline options and exit. */
    public bool $helpGeneral = false;
    /**
     * Format/driver name to be attempted to open the input file(s). It is generally not necessary to specify it, but it can be used to skip automatic driver detection, when it fails to select the appropriate driver. This option can be repeated several times to specify several candidate drivers. Note that it does not force those drivers to open the dataset. In particular, some drivers have requirements on file extensions.
     *
     * Added in version 3.2.
     */
    public ?VectorFormat $if;
    /** Open the data source in read-only mode. */
    public bool $ro = false;
    /**
     * Enable listing of features. This has the opposite effect of -so.
     * This option should be used with caution if using the library function GDALVectorInfo() and/or -json, as the whole output of ogrinfo will be built in memory. Consequently, when used on a large collection of features, RAM may be exhausted.
     *
     * Added in version 3.7.
     */
    public bool $features = false;
    /**
     * Added in version 3.9.
     *
     * Limit the number of features per layer.
     */
    public ?int $limit = null;
    /** Quiet verbose reporting of various information, including coordinate system, layer schema, extents, and feature count. */
    public bool $q = false;
    /**
     * An attribute query in a restricted form of the queries used in the SQL WHERE statement. Only features matching the attribute query will be reported. Starting with GDAL 2.1, the @<filename> syntax can be used to indicate that the content is in the pointed filename.
     *
     * Example of -where and quoting:
     *   -where "\"Corner Point Identifier\" LIKE '%__00_00'"
     *
     * Note: -dialect is ignored with -where. Use -sql instead of -where if you want to use -dialect.
     */
    public string $where = '';
    /** The area of interest. Only features within the rectangle will be reported. */
    public ?SpatialQueryExtent $spat = null;
    /** Name of the geometry field on which the spatial filter operates. */
    public string $geomfield = '';
    /** If provided, only the feature with this feature id will be reported. Operates exclusive of the spatial or attribute queries. Note: if you want to select several features based on their feature id, you can also use the fact the 'fid' is a special field recognized by OGR SQL. So, -where "fid in (1,3,5)" would select features 1, 3 and 5. */
    public string $fid = '';
    /** Execute the indicated SQL statement and return the result. Starting with GDAL 2.1, the @<filename> syntax can be used to indicate that the content is in the pointed filename (e.g. "@my_select.txt" where my_select.txt is a file in the current directory). Data can also be edited with SQL INSERT, UPDATE, DELETE, DROP TABLE, ALTER TABLE etc. Editing capabilities depend on the selected dialect with -dialect. */
    public string $sql = '';
    /** SQL dialect. In some cases can be used to use (unoptimized) OGR SQL dialect instead of the native SQL of an RDBMS by passing the OGRSQL dialect value. The SQL SQLite dialect can be selected with the SQLITE and INDIRECT_SQLITE dialect values, and this can be used with any datasource. */
    public ?SqlDialect $dialect = null;
    /** List all layers (used instead of having to give layer names as arguments). In the default text output, this also enables listing all features, which can be disabled with -so. In JSON output, -al is implicit, but listing of features must be explicitly enabled with -features. */
    public bool $al = false;
    /**
     * Enable random layer reading mode, i.e. iterate over features in the order they are found in the dataset, and not layer per layer. This can be significantly faster for some formats (for example OSM, GMLAS). -rl cannot be used with -sql.
     *
     * Added in version 2.2.
     */
    public bool $rl = false;
    /** Summary Only: suppress listing of individual features and show only summary information like projection, schema, feature count and extents. In JSON output, -so is implicit and listing of features can be enabled with -features. */
    public bool $so = false;
    /** If set to NO, the feature dump will not display field values. Default value is YES. */
    public bool $fields = true;
    /**
     * Added in version 3.3.
     *
     * Display details about a field domain.
     */
    public string $fielddomain = '';
    /** If set to NO, the feature dump will not display the geometry. If set to SUMMARY, only a summary of the geometry will be displayed. If set to YES or ISO_WKT, the geometry will be reported in full OGC WKT format. If set to WKT the geometry will be reported in legacy WKT. Default value is YES. (WKT and ISO_WKT are available starting with GDAL 2.1, which also changes the default to ISO_WKT) */
    public string $geom = '';
    /**
     * List all vector formats supported by this GDAL build (read-only and read-write) and exit. The format support is indicated as follows:
     *
     *   - ro is read-only driver
     *   - rw is read or write (i.e. supports GDALDriver::CreateCopy())
     *   - rw+ is read, write and update (i.e. supports GDALDriver::Create())
     *   - A v is appended for formats supporting virtual IO (/vsimem, /vsigzip, /vsizip, etc).
     *   - A s is appended for formats supporting subdatasets.
     *
     * The order in which drivers are listed is the one in which they are registered, which determines the order in which they are successively probed when opening a dataset. Most of the time, this order does not matter, but in some situations, several drivers may recognize the same file. The -if option of some utilities can be specified to restrict opening the dataset with a subset of drivers (generally one). Note that it does not force those drivers to open the dataset. In particular, some drivers have requirements on file extensions. Alternatively, the GDAL_SKIP configuration option can also be used to exclude one or several drivers.
     */
    public bool $formats = false;
    /**
     * Dataset open option (format specific).
     * If a driver supports the OGR_SCHEMA open option, it can be used to partially or completely override the auto-detected schema (i.e. which fields are read, with which types, subtypes, length, precision etc.) of the dataset.
     * The value of this option is a JSON string or a path to a JSON file that complies with the OGR_SCHEMA open option schema definition
     */
    public array $oo = [];
    /** Suppress metadata printing. Some datasets may contain a lot of metadata strings. */
    public bool $nomd = false;
    /** List all metadata domains available for the dataset. */
    public bool $listmdd = false;
    /** Report metadata for the specified domain. all can be used to report metadata in all domains. */
    public string $mdd = '';
    /** Suppress feature count printing. */
    public bool $nocount = false;
    /** Suppress spatial extent printing. */
    public bool $noextent = false;
    /**
     * Added in version 3.9.
     *
     * Request a 3D extent to be reported (the default is 2D only). Note that this operation might be slower than requesting the 2D extent, depending on format and driver capabilities.
     */
    public bool $extend3D = false;
    /**
     * Suppress layer geometry type printing.
     *
     * Added in version 3.1.
     */
    public bool $nogeomtype = false;
    /**
     * The WKT format used to display the SRS. Currently supported values for the format are:
     *
     *   - WKT1
     *   - WKT2 (latest WKT version, currently WKT2_2018)
     *   - WKT2_2015
     *   - WKT2_2018
     *
     * Added in version 3.0.0.
     */
    public string $wkt_format = '';
    /**
     * Display the output in json format, conforming to the ogrinfo_output.schema.json ogrinfo_output.schema.json schema.
     *
     * Added in version 3.7.
     */
    public bool $json = false;
}
