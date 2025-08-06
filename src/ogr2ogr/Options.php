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

namespace Datasmart\GDAL\ogr2ogr;

use Datasmart\GDAL\Types\ControlPoint;
use Datasmart\GDAL\Types\CoordinateDimension;
use Datasmart\GDAL\Types\FieldType;
use Datasmart\GDAL\Types\LayerGeometryType;
use Datasmart\GDAL\Types\SpatialQueryExtent;
use Datasmart\GDAL\Types\SqlDialect;
use Datasmart\GDAL\Types\VectorFormat;

/**
 * @author Jonathan Beliën <jbe@geo6.be>
 */
class Options
{
    /** Show help message and exit */
    public bool $help = false;
    /** Gives a brief usage message for the generic GDAL commandline options and exit. */
    public bool $helpGeneral = false;
    /** Continue after a failure, skipping the failed feature. */
    public bool $skipfailures = false;
    /** Run the OGRGeometry::IsValid() operation on geometries to check if they are valid regarding the rules of the Simple Features specification. If they are not, the feature is skipped. This check is done after all other geometry operations. */
    public bool $skipinvalid = false;
    /** Append to existing layer instead of creating new. This option also enables -update. */
    public bool $append = false;
    /** Open existing output datasource in update mode rather than trying to create a new one */
    public bool $update = false;
    /**
     * Comma-delimited list of fields from input layer to copy to the new layer.
     *
     * Starting with GDAL 3.9, field names with spaces, commas or double-quote should be surrounded with a starting and ending double-quote character, and double-quote characters in a field name should be escaped with backslash.
     *
     * Depending on the shell used, this might require further quoting. For example, to select regular_field, a_field_with space, and comma and a field with " double quote with a Unix shell:
     *
     * -select "regular_field,\"a_field_with space, and comma\",\"a field with \\\" double quote\""
     * A field is only selected once, even if mentioned several times in the list and if the input layer has duplicate field names.
     *
     * Geometry fields can also be specified in the list.
     *
     * All fields are selected when -select is not specified. Specifying the empty string can be used to disable selecting any attribute field, and only keep geometries.
     *
     * Note this setting cannot be used together with -append. To control the selection of fields when appending to a layer, use -fieldmap or -sql.
     */
    public string $select = '';
    /** Attribute query (like SQL WHERE). Starting with GDAL 2.1, the @filename syntax can be used to indicate that the content is in the pointed filename. */
    public string $where = '';
    /** Display progress on terminal. Only works if input layers have the "fast feature count" capability. */
    public bool $progress = false;
    /** SQL statement to execute. The resulting table/layer will be saved to the output. Starting with GDAL 2.1, the @filename syntax can be used to indicate that the content is in the pointed filename. (Cannot be used with -spat_srs.) */
    public string $sql = '';
    /** SQL dialect. In some cases can be used to use the (unoptimized) OGR SQL dialect instead of the native SQL of an RDBMS by passing the OGRSQL dialect value. The SQL SQLite dialect can be chosen with the SQLITE and INDIRECT_SQLITE dialect values, and this can be used with any datasource. */
    public ?SqlDialect $dialect = null;
    /** Use the FID of the source features instead of letting the output driver automatically assign a new one (for formats that require a FID). If not in append mode, this behavior is the default if the output driver has a FID layer creation option, in which case the name of the source FID column will be used and source feature IDs will be attempted to be preserved. This behavior can be disabled by setting -unsetFid. This option is not compatible with -explodecollections. */
    public bool $preserve_fid = false;
    /**
     * If provided, only the feature with the specified feature id will be processed.
     * Operates exclusive of the spatial or attribute queries.
     * Note: if you want to select several features based on their feature id, you can also use the fact the 'fid' is a special field recognized by OGR SQL. So, -where "fid in (1,3,5)" would select features 1, 3 and 5.
     */
    public string $fid = '';
    /** Limit the number of features per layer */
    public ?int $limit = null;
    /** spatial query extents, in the SRS of the source layer(s) (or the one specified with -spat_srs). Only features whose geometry intersects the extents will be selected. The geometries will not be clipped unless -clipsrc is specified. */
    public ?SpatialQueryExtent $spat = null;
    /** Override spatial filter SRS. (Cannot be used with -sql.) */
    public string $spat_srs = '';
    /** Name of the geometry field on which the spatial filter operates on. */
    public string $geomfield = '';
    /** Assign an output SRS, but without reprojecting (use -t_srs to reproject)
     *  The coordinate reference systems that can be passed are anything supported by the OGRSpatialReference::SetFromUserInput() call, which includes EPSG Projected, Geographic or Compound CRS (i.e. EPSG:4296), a well known text (WKT) CRS definition, PROJ.4 declarations, or the name of a .prj file containing a WKT CRS definition.
     */
    public string $a_srs = '';
    /**
     * Added in version 3.4.
     *
     * Assign a coordinate epoch, linked with the output SRS. Useful when the output SRS is a dynamic CRS. Only taken into account if -a_srs is used.
     */
    public string $a_coord_epoch = '';
    /** Reproject/transform to this SRS on output, and assign it as output SRS.
     *  A source SRS must be available for reprojection to occur. The source SRS will be by default the one found in the source layer when it is available, or as overridden by the user with -s_srs
     *  The coordinate reference systems that can be passed are anything supported by the OGRSpatialReference::SetFromUserInput() call, which includes EPSG Projected, Geographic or Compound CRS (i.e. EPSG:4296), a well known text (WKT) CRS definition, PROJ.4 declarations, or the name of a .prj file containing a WKT CRS definition.
     */
    public string $t_srs = '';
    /**
     * Added in version 3.4.
     *
     * Assign a coordinate epoch, linked with the output SRS. Useful when the output SRS is a dynamic CRS. Only taken into account if -t_srs is used. It is also mutually exclusive with -a_coord_epoch.
     * Before PROJ 9.4, -s_coord_epoch and -t_coord_epoch were mutually exclusive, due to lack of support for transformations between two dynamic CRS.
     */
    public string $t_coord_epoch = '';
    /** Override source SRS. If not specified the SRS found in the input layer will be used. This option has only an effect if used together with -t_srs to reproject.
     *  The coordinate reference systems that can be passed are anything supported by the OGRSpatialReference::SetFromUserInput() call, which includes EPSG Projected, Geographic or Compound CRS (i.e. EPSG:4296), a well known text (WKT) CRS definition, PROJ.4 declarations, or the name of a .prj file containing a WKT CRS definition.
     */
    public string $s_srs = '';
    /**
     * Added in version 3.4.
     *
     * Assign a coordinate epoch, linked with the source SRS. Useful when the source SRS is a dynamic CRS. Only taken into account if -s_srs is used.
     * Before PROJ 9.4, -s_coord_epoch and -t_coord_epoch were mutually exclusive, due to lack of support for transformations between two dynamic CRS.
     */
    public string $s_coord_epoch = '';
    /**
     * Added in version 3.0.
     *
     * A PROJ string (single step operation or multiple step string starting with +proj=pipeline), a WKT2 string describing a CoordinateOperation, or a urn:ogc:def:coordinateOperation:EPSG::XXXX URN overriding the default transformation from the source to the target CRS.
     *
     * It must take into account the axis order of the source and target CRS, that is typically include a step proj=axisswap order=2,1 at the beginning of the pipeline if the source CRS has northing/easting axis order, and/or at the end of the pipeline if the target CRS has northing/easting axis order.
     */
    public string $ct = '';
    /**
     * Added in version 3.11.
     *
     * Specify a coordinate operation option that influences how PROJ selects coordinate operations when -ct is not set.
     *
     * The following options are available:
     *
     *  - ONLY_BEST``=``YES/NO. By default, (at least in the PROJ 9.x series), PROJ may use coordinate operations that are not the "best" if resources (typically grids) needed to use them are missing. It will then fallback to other coordinate operations that have a lesser accuracy, for example using Helmert transformations, or in the absence of such operations, to ones with potential very rough accuracy, using "ballpark" transformations (see https://proj.org/glossary.html). When calling this method with YES, PROJ will only consider the "best" operation, and error out (at Transform() time) if they cannot be used. This method may be used together with ALLOW_BALLPARK``=``NO to only allow best operations that have a known accuracy. Note that this method has no effect on PROJ versions before 9.2. The default value for this option can be also set with the PROJ_ONLY_BEST_DEFAULT environment variable, or with the only_best_default setting of proj.ini. Setting ONLY_BEST=YES/NO overrides such default value.
     *
     *  - ALLOW_BALLPARK``=``YES/NO. Whether ballpark coordinate operations are allowed. Default is YES.
     *
     *  - WARN_ABOUT_DIFFERENT_COORD_OP``=``YES/NO. Can be set to NO to avoid GDAL warning when different coordinate operations are used to transform the different geometries of the dataset (or part of the same geometry). Default is YES.
     */
    public array $ct_opt = [];
    /** Format/driver name to be attempted to open the input file(s). It is generally not necessary to specify it, but it can be used to skip automatic driver detection, when it fails to select the appropriate driver. This option can be repeated several times to specify several candidate drivers. Note that it does not force those drivers to open the dataset. In particular, some drivers have requirements on file extensions.

    Added in version 3.8.2. (docs claim version 3.2, but code to run the option wasn't added until 3.8.2) */
    public ?VectorFormat $if = null;
    /** Output file format name, e.g. ESRI Shapefile, MapInfo File, PostgreSQL. Starting with GDAL 2.3, if not specified, the format is guessed from the extension (previously was ESRI Shapefile). */
    public ?VectorFormat $f = null;
    /** Delete the output layer and recreate it empty */
    public bool $overwrite = false;
    /** @var string[]
     *  Dataset creation option (format specific)
     */
    public $dsco = [];
    /** @var string[]
     *  Layer creation option (format specific)
     */
    public $lco = [];
    /** Assign an alternate name to the new layer */
    public string $nln = '';
    /**
     * Define the geometry type for the created layer. One of NONE, GEOMETRY, POINT, LINESTRING, POLYGON, GEOMETRYCOLLECTION, MULTIPOINT, MULTIPOLYGON, MULTILINESTRING, CIRCULARSTRING, COMPOUNDCURVE, CURVEPOLYGON, MULTICURVE, and MULTISURFACE non-linear geometry types. Add Z, M, or ZM to the type name to specify coordinates with elevation, measure, or elevation and measure. PROMOTE_TO_MULTI can be used to automatically promote layers that mix polygon or multipolygons to multipolygons, and layers that mix linestrings or multilinestrings to multilinestrings. Can be useful when converting shapefiles to PostGIS and other target drivers that implement strict checks for geometry types. CONVERT_TO_LINEAR can be used to to convert non-linear geometry types into linear geometry types by approximating them, and CONVERT_TO_CURVE to promote a non-linear type to its generalized curve type (POLYGON to CURVEPOLYGON, MULTIPOLYGON to MULTISURFACE, LINESTRING to COMPOUNDCURVE, MULTILINESTRING to MULTICURVE). Starting with version 2.1 the type can be defined as measured ("25D" remains as an alias for single "Z"). Some forced geometry conversions may result in invalid geometries, for example when forcing conversion of multi-part multipolygons with -nlt POLYGON, the resulting polygon will break the Simple Features rules.
     *
     * Starting with GDAL 3.0.5, -nlt CONVERT_TO_LINEAR and -nlt PROMOTE_TO_MULTI can be used simultaneously.
     */
    public ?LayerGeometryType $nlt = null;
    /**
     * Force the coordinate dimension to val (valid values are XY, XYZ, XYM, and XYZM - for backwards compatibility 2 is an alias for XY and 3 is an alias for XYZ). This affects both the layer geometry type, and feature geometries. The value can be set to layer_dim to instruct feature geometries to be promoted to the coordinate dimension declared by the layer. Support for M was added in GDAL 2.1.
     */
    public ?CoordinateDimension $dim = null;
    /** Group n features per transaction (default 100 000). Increase the value for better performance when writing into DBMS drivers that have transaction support. n can be set to unlimited to load the data into a single transaction. */
    public ?int $gt = null;
    /** Dataset open option (format specific).
    *   If a driver supports the OGR_SCHEMA open option, it can be used to partially or completely override the auto-detected schema (i.e. which fields are read, with which types, subtypes, length, precision etc.) of the dataset.
    *   The value of this option is a JSON string or a path to a JSON file that complies with the OGR_SCHEMA open option schema definition
    */
    public array $oo = [];
    /** Destination dataset open option (format specific), only valid in -update mode. */
    public array $doo = [];
    /** Force the use of a dataset level transaction (for drivers that support such mechanism), especially for drivers such as FileGDB that only support dataset level transaction in emulation mode. */
    public bool $ds_transaction = false;
    /**
     *
     * Clip geometries (before potential reprojection) to one of the following:
     * - the specified bounding box (expressed in source SRS)
     * - a WKT geometry (POLYGON or MULTIPOLYGON expressed in source SRS)
     * - one or more geometries selected from a datasource
     * - the spatial extent of the -spat option if you use the spat_extent keyword.
     * When specifying a datasource, you will generally want to use -clipsrc in combination of the -clipsrclayer, -clipsrcwhere or -clipsrcsql options.
     */
    public string $clipsrc = '';
    /** Select desired geometries from the source clip datasource using an SQL query. */
    public string $clipsrcsql = '';
    /** Select the named layer from the source clip datasource. */
    public string $clipsrclayer = '';
    /** Restrict desired geometries from the source clip layer based on an attribute query. */
    public string $clipsrcwhere = '';
    /**
     * Clip geometries (after potential reprojection) to one of the following:
     * - the specified bounding box (expressed in destination SRS)
     * - a WKT geometry (POLYGON or MULTIPOLYGON expressed in destination SRS)
     * - one or more geometries selected from a datasource
     * When specifying a datasource, you will generally want to use -clipdst in combination with the -clipdstlayer, -clipdstwhere or -clipdstsql options.
     * */
    public string $clipdst = '';
    /** Select desired geometries from the destination clip datasource using an SQL query. */
    public string $clipdstsql = '';
    /** Select the named layer from the destination clip datasource. */
    public string $clipdstlayer = '';
    /** Restrict desired geometries from the destination clip layer based on an attribute query. */
    public string $clipdstwhere = '';
    /** Split geometries crossing the dateline meridian (long. = +/- 180deg) */
    public bool $wrapdateline = false;
    /** Offset from dateline in degrees (default long. = +/- 10deg, geometries within 170deg to -170deg will be split) */
    public ?int $datelineoffset = null;
    /**
     * Converts date time values from the timezone specified in the source value to the target timezone expressed with -dateTimeTo. Datetime whose timezone is unknown or localtime are not modified.
     * HH must be in the [0,14] range and MM=00, 15, 30 or 45.
     */
    public string $dateTimeTo = '';
    /**
     * Distance tolerance for simplification. Note: the algorithm used preserves topology per feature, in particular for polygon geometries, but not for a whole layer.
     * The specified value of this option is the tolerance used to merge consecutive points of the output geometry using the OGRGeometry::SimplifyPreserveTopology() method The unit of the distance is in georeferenced units of the source vector dataset. This option is applied before the reprojection implied by -t_srs
     */
    public ?int $simplify = null;
    /** The specified value of this option is the maximum distance between two consecutive points of the output geometry before intermediate points are added. The unit of the distance is georeferenced units of the source layer. This option is applied before the reprojection implied by -t_srs */
    public ?int $segmentize = null;
    /** Run the OGRGeometry::MakeValid() operation, followed by OGRGeometryFactory::removeLowerDimensionSubGeoms(), on geometries to ensure they are valid regarding the rules of the Simple Features specification. */
    public bool $makevalid = false;
    /** This is a specialized version of -append. Contrary to -append, -addfields has the effect of adding, to existing target layers, the new fields found in source layers. This option is useful when merging files that have non-strictly identical structures. This might not work for output formats that don't support adding fields to existing non-empty layers. Note that if you plan to use -addfields, you may need to combine it with -forceNullable, including for the initial import. */
    public bool $addfields = false;
    /** Can be specified to prevent the name of the source FID column and source feature IDs from being re-used for the target layer. This option can for example be useful if selecting source features with a ORDER BY clause. */
    public bool $unsetFid = false;
    /** Do field name matching between source and existing target layer in a more relaxed way if the target driver has an implementation for it. */
    public bool $relaxedFieldNameMatch = false;
    /** Do not propagate not-nullable constraints to target layer if they exist in source layer. */
    public bool $forceNullable = false;
    /** Do not propagate default field values to target layer if they exist in source layer. */
    public bool $unsetDefault = false;
    /**
     * @var FieldType[]
     * Converts any field of the specified type to a field of type string in the destination layer. Valid types are : Integer, Integer64, Real, String, Date, Time, DateTime, Binary, IntegerList, Integer64List, RealList, StringList. Special value All can be used to convert all fields to strings. This is an alternate way to using the CAST operator of OGR SQL, that may avoid typing a long SQL query. Note that this does not influence the field types used by the source driver, and is only an afterwards conversion. Also note that this option is without effects on fields whose presence and type is hard-coded in the output driver (e.g KML, GPX). For an alternative way to manipulate field types earlier in the process while they are read from the input dataset see -oo OGR_SCHEMA (only available for a limited set of formats).
     */
    public array $fieldTypeToString = [];
    /** Set field width and precision to 0. */
    public bool $unsetFieldWidth = false;
    /** Converts any field of the specified type to another type. Valid types are : Integer, Integer64, Real, String, Date, Time, DateTime, Binary, IntegerList, Integer64List, RealList, StringList. Types can also include subtype between parenthesis, such as Integer(Boolean), Real(Float32), ... Special value All can be used to convert all fields to another type. This is an alternate way to using the CAST operator of OGR SQL, that may avoid typing a long SQL query. This is a generalization of -fieldTypeToString. Note that this does not influence the field types used by the source driver, and is only an afterwards conversion. Also note that this option is without effects on fields whose presence and type is hard-coded in the output driver (e.g KML, GPX). For an alternative way to manipulate field types earlier in the process while they are read from the input dataset see -oo OGR_SCHEMA (only available for a limited set of formats). */
    public string $mapFieldType = '';
    /** Specifies the list of field indexes to be copied from the source to the destination. The (n)th value specified in the list is the index of the field in the target layer definition in which the n(th) field of the source layer must be copied. Index count starts at zero. To omit a field, specify a value of -1. There must be exactly as many values in the list as the count of the fields in the source layer. We can use the 'identity' setting to specify that the fields should be transferred by using the same order. This setting should be used along with the -append setting. */
    public string $fieldmap = '';
    /** Split fields of type StringList, RealList or IntegerList into as many fields of type String, Real or Integer as necessary. */
    public bool $splitlistfields = false;
    /** To be combined with -splitlistfields to limit the number of subfields created for each split field. */
    public ?int $maxsubfields = null;
    /** Produce one feature for each geometry in any kind of geometry collection in the source file, applied after any -sql option. This options is not compatible with -preserve_fid but -sql "SELECT fid AS original_fid, * FROM ..." can be used to store the original FID if needed. */
    public bool $explodecollections = false;
    /** Uses the specified field to fill the Z coordinate of geometries. */
    public string $zfield = '';
    /**
     * @var ControlPoint[]
     * Use the indicated ground control point to compute a coordinate transformation. The transformation method can be selected by specifying the -order or -tps options. Note that unlike raster tools such as gdal_edit or gdal_translate, GCPs are not added to the output dataset. This option may be provided multiple times to provide a set of GCPs (at least 2 GCPs are needed).
     */
    public array $gcp = [];
    /** Order of polynomial used for warping (1 to 3). The default is to select a polynomial order based on the number of GCPs. */
    public ?int $order = null;
    /** Force use of thin plate spline transformer based on available GCPs. */
    public bool $tps = false;
    /** To disable copying of metadata from source dataset and layers into target dataset and layers, when supported by output driver. */
    public bool $nomd = false;
    /** Passes a metadata key and value to set on the output dataset, when supported by output driver. */
    public array $mo = [];
    /**
     * To disable copying of native data, i.e. details of source format not captured by OGR abstraction, that are otherwise preserved by some drivers (like GeoJSON) when converting to same format.
     * Added in version 2.1.
     */
    public bool $noNativeData = false;
    /**
     * Added in version 3.6.
     * Variant of -append where the OGRLayer::UpsertFeature() operation is used to insert or update features instead of appending with OGRLayer::CreateFeature().
     * This is currently implemented only in a few drivers: GPKG -- GeoPackage vector and MongoDBv3.
     * The upsert operation uses the FID of the input feature, when it is set and is a "significant" (that is the FID column name is not the empty string), as the key to update existing features. It is crucial to make sure that the FID in the source and target layers are consistent.
     * For the GPKG driver, it is also possible to upsert features whose FID is unset or non-significant (-unsetFid can be used to ignore the FID from the source feature), when there is a UNIQUE column that is not the integer primary key.
     */
    public bool $upsert = false;
    /**
     * Added in version 3.9.
     *
     * Set/override the geometry X/Y coordinate resolution. If only a numeric value is specified, it is assumed to be expressed in the units of the target SRS. The m, mm or deg suffixes can be specified to indicate that the value must be interpreted as being in meter, millimeter or degree.
     * When specifying this option, the OGRGeometry::SetPrecision() method is run on geometries (that are not curves) before passing them to the output driver, to avoid generating invalid geometries due to the potentially reduced precision (unless the OGR_APPLY_GEOM_SET_PRECISION configuration option is set to NO)
     * If neither this option nor -unsetCoordPrecision are specified, the coordinate resolution of the source layer, if available, is used.
     */
    public string $xyRes = '';
    /**
     * Added in version 3.9.
     *
     * Set/override the geometry Z coordinate resolution. If only a numeric value is specified, it is assumed to be expressed in the units of the target SRS. The m or mm suffixes can be specified to indicate that the value must be interpreted as being in meter or millimeter. If neither this option nor -unsetCoordPrecision are specified, the coordinate resolution of the source layer, if available, is used.
     */
    public string $zRes = '';
    /**
     * Added in version 3.9.
     *
     * Set/override the geometry M coordinate resolution. If neither this option nor -unsetCoordPrecision are specified, the coordinate resolution of the source layer, if available, is used.
     */
    public string $mRes = '';

    public bool $unsetCoordPrecision = false;
    /**
     * Added in version 3.3.
     *
     * Treat empty string values as null.
     */
    public bool $emptyStrAsNull = false;
    /**
     * Added in version 3.3.
     *
     * When this is specified, any selected field that is linked to a coded field domain will be accompanied by an additional field ({dstfield}_resolved), that will contain the description of the coded value.
     */
    public bool $resolveDomains = false;


    /**
     * This value controls the underlying Symfony Process' timeout value
     */
    public ?int $processTimeout = null;

    public const array VALID_POLYNOMIAL_ORDERS = [1, 2, 3];
}
