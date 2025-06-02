<?php

namespace Datasmart\GDAL\Types;

enum VectorFormat: string
{
    /** Artificial intelligence powered vector driver */
    case AIVector = 'AIVector';
    /** AmigoCloud*/
    case AmigoCloud = 'AmigoCloud';
    /** (Geo)Arrow IPC File Format / Stream */
	case Arrow = 'Arrow';
    /** Arc/Info Binary Coverage */
    case AVCBIN = 'AVCBIN';
    /** Arc/Info E00 (ASCII) Coverage */
    case AVCE00 = 'AVCE00';
    /** Carto */
	case CARTO = 'CARTO';
    /** Comma Separated Value (.csv) */
    case CSV = 'CSV';
    /** OGC CSW (Catalog Service for the Web) */
    case CSW = 'CSW';
    /** Microstation DGN */
    case DGN = 'DGN';
	/** Microstation DGN v8 */
    case DGNv8 = 'DGNv8';
    /** AutoCAD DWG */
    case DWG = 'DWG';
    /** AutoCAD DXF */
    case DXF = 'DXF';
    /** EDIGEO */
    case EDIGEO = 'EDIGEO';
    /** Google Earth Engine Data */
    case EEDA = 'EEDA';
    /** Elasticsearch: Geographically Encoded Objects for Elasticsearch */
    case Elasticsearch = 'Elasticsearch';
    /** ESRI Shapefile / DBF */
    case DBF = 'ESRI Shapefile';
    /** ESRIJSON / FeatureService driver */
    case ESRIJSON = 'ESRIJSON';
    /** ESRI File Geodatabase (FileGDB) */
    case FileGDB = 'FileGDB';
    /** FlatGeobuf */
    case FlatGeobuf = 'FlatGeobuf';
    /** GeoJSON */
    case GeoJSON = 'GeoJSON';
    /** GeoJSONSeq: sequence of GeoJSON features */
    case GeoJSONSeq = 'GeoJSONSeq';
    /** GeoRSS : Geographically Encoded Objects for RSS feeds */
    case GeoRSS = 'GeoRSS';
    /** Geography Markup Language */
    case GML = 'GML';
    /** Geography Markup Language (GML) driven by application schemas */
    case GMLAS = 'GMLAS';
    /** GMT ASCII Vectors (.gmt) */
    case GMT = 'GMT';
    /** GPSBabel */
    case GPSBabel = 'GPSBabel';
    /** GPS Exchange Format */
    case GPX = 'GPX';
    /** General Transit Feed Specification */
    case GTFS = 'GTFS';
    /** SAP HANA */
    case HANA = 'HANA';
    /** IDB */
    case IDB = 'IDB';
    /** Idrisi Vector (.VCT) */
    case IDRISI = 'IDRISI';
    /** "INTERLIS 1" and "INTERLIS 2" drivers */
    case INTERLIS1 = 'INTERLIS 1';
    /** JML: OpenJUMP JML format */
    case JML = 'JML';
    /** OGC Features and Geometries JSON */
    case JSONFG = 'JSONFG';
    /** Keyhole Markup Language */
    case KML = 'KML';
    /** LIBKML Driver (.kml .kmz) */
    case LIBKML = 'LIBKML';
    /** Dutch Kadaster LV BAG 2.0 Extract */
    case LVBAG = 'LVBAG';
    /** MapInfo TAB and MIF/MID */
    case MapInfoFile = 'MapInfo File';
    /** MapML */
    case MapML = 'MapML';
    /** Memory
     * @deprecated
     */
    case Memory = 'Memory';
    /** MiraMon Vector */
    case MiraMonVector = 'MiraMonVector';
    /** MongoDBv3 */
    case MongoDBv3 = 'MongoDBv3';
    /** Microsoft SQL Server Spatial Database */
    case MSSQLSpatial = 'MSSQLSpatial';
    /** MVT: Mapbox Vector Tiles */
    case MVT = 'MVT';
    /** MySQL */
    case MySQL = 'MySQL';
    /** ALKIS */
    case NAS = 'NAS';
    /** OGC API */
    case OAPIF = 'OAPIF';
    /** Oracle Spatial */
    case OCI = 'OCI';
    /** ODBC RDBMS */
    case ODBC = 'ODBC';
    /** Open Document Spreadsheet */
    case ODS = 'ODS';
    /** OpenStreetMap XML and PBF */
    case OSM = 'OSM';
    /** (Geo)Parquet */
    case Parquet = 'Parquet';
    /** PostgreSQL SQL Dump */
    case PGDump = 'PGDump';
    /** ESRI Personal GeoDatabase */
    case PGeo = 'PGeo';
    /** PLScenes (Planet Labs Scenes/Catalog API) */
    case PLScenes = 'PLScenes';
    /** PMTiles */
    case PMTiles = 'PMTiles';
    /** PostgreSQL / PostGIS */
    case PostgreSQL = 'PostgreSQL';
    /** IHO S-57 (ENC) */
    case S57 = 'S57';
    /** Selafin files */
    case Selafin = 'Selafin';
    /** Norwegian SOSI Standard */
    case SOSI = 'SOSI';
    /** SQLite/Spatialite RDMBS */
    case SQLite = 'SQLite';
    /** SXF */
    case SXF = 'SXF';
    /** TopoJSON driver */
    case TopoJSON = 'TopoJSON';
    /** VDV-451/VDV-452/INTREST */
    case VDV = 'VDV';
    /** Czech Cadastral Exchange Data Format */
    case VFK = 'VFK';
    /** WAsP .map format */
    case WAsP = 'WAsP';
    /** OGC WFS service */
    case WFS = 'WFS';
    /** MS Excel format */
    case XLS = 'XLS';
    /** MS Office Open XML spreadsheet */
    case XLSX = 'XLSX';
    /** OpenDRIVE Road Description Format */
    case XODR = 'XODR';
}
