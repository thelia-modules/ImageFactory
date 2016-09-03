<?php

namespace ImageFactory\Model\Map;

use ImageFactory\Model\ImageFactory;
use ImageFactory\Model\ImageFactoryQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'image_factory' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ImageFactoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'ImageFactory.Model.Map.ImageFactoryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'image_factory';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ImageFactory\\Model\\ImageFactory';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ImageFactory.Model.ImageFactory';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 27;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 27;

    /**
     * the column name for the ID field
     */
    const ID = 'image_factory.ID';

    /**
     * the column name for the CODE field
     */
    const CODE = 'image_factory.CODE';

    /**
     * the column name for the SOURCES field
     */
    const SOURCES = 'image_factory.SOURCES';

    /**
     * the column name for the DESTINATION field
     */
    const DESTINATION = 'image_factory.DESTINATION';

    /**
     * the column name for the JUST_SYMLINK field
     */
    const JUST_SYMLINK = 'image_factory.JUST_SYMLINK';

    /**
     * the column name for the WIDTH field
     */
    const WIDTH = 'image_factory.WIDTH';

    /**
     * the column name for the HEIGHT field
     */
    const HEIGHT = 'image_factory.HEIGHT';

    /**
     * the column name for the QUALITY field
     */
    const QUALITY = 'image_factory.QUALITY';

    /**
     * the column name for the BACKGROUND_COLOR field
     */
    const BACKGROUND_COLOR = 'image_factory.BACKGROUND_COLOR';

    /**
     * the column name for the BACKGROUND_OPACITY field
     */
    const BACKGROUND_OPACITY = 'image_factory.BACKGROUND_OPACITY';

    /**
     * the column name for the RESIZE_MODE field
     */
    const RESIZE_MODE = 'image_factory.RESIZE_MODE';

    /**
     * the column name for the ROTATION field
     */
    const ROTATION = 'image_factory.ROTATION';

    /**
     * the column name for the RESAMPLING_FILTER field
     */
    const RESAMPLING_FILTER = 'image_factory.RESAMPLING_FILTER';

    /**
     * the column name for the PREFIX field
     */
    const PREFIX = 'image_factory.PREFIX';

    /**
     * the column name for the SUFFIX field
     */
    const SUFFIX = 'image_factory.SUFFIX';

    /**
     * the column name for the LAYERS field
     */
    const LAYERS = 'image_factory.LAYERS';

    /**
     * the column name for the EFFECTS field
     */
    const EFFECTS = 'image_factory.EFFECTS';

    /**
     * the column name for the PIXEL_RATIOS field
     */
    const PIXEL_RATIOS = 'image_factory.PIXEL_RATIOS';

    /**
     * the column name for the INTERLACE field
     */
    const INTERLACE = 'image_factory.INTERLACE';

    /**
     * the column name for the PERSIST field
     */
    const PERSIST = 'image_factory.PERSIST';

    /**
     * the column name for the ALLOW_ZOOM field
     */
    const ALLOW_ZOOM = 'image_factory.ALLOW_ZOOM';

    /**
     * the column name for the IMAGINE_LIBRARY_CODE field
     */
    const IMAGINE_LIBRARY_CODE = 'image_factory.IMAGINE_LIBRARY_CODE';

    /**
     * the column name for the IMAGE_NOT_FOUND_SOURCE field
     */
    const IMAGE_NOT_FOUND_SOURCE = 'image_factory.IMAGE_NOT_FOUND_SOURCE';

    /**
     * the column name for the IMAGE_NOT_FOUND_DESTINATION_FILE_NAME field
     */
    const IMAGE_NOT_FOUND_DESTINATION_FILE_NAME = 'image_factory.IMAGE_NOT_FOUND_DESTINATION_FILE_NAME';

    /**
     * the column name for the DISABLE_I18N_PROCESSING field
     */
    const DISABLE_I18N_PROCESSING = 'image_factory.DISABLE_I18N_PROCESSING';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'image_factory.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'image_factory.UPDATED_AT';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    // i18n behavior

    /**
     * The default locale to use for translations.
     *
     * @var string
     */
    const DEFAULT_LOCALE = 'en_US';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Code', 'Sources', 'Destination', 'JustSymlink', 'Width', 'Height', 'Quality', 'BackgroundColor', 'BackgroundOpacity', 'ResizeMode', 'Rotation', 'ResamplingFilter', 'Prefix', 'Suffix', 'Layers', 'Effects', 'PixelRatios', 'Interlace', 'Persist', 'AllowZoom', 'ImagineLibraryCode', 'ImageNotFoundSource', 'ImageNotFoundDestinationFileName', 'DisableI18nProcessing', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'code', 'sources', 'destination', 'justSymlink', 'width', 'height', 'quality', 'backgroundColor', 'backgroundOpacity', 'resizeMode', 'rotation', 'resamplingFilter', 'prefix', 'suffix', 'layers', 'effects', 'pixelRatios', 'interlace', 'persist', 'allowZoom', 'imagineLibraryCode', 'imageNotFoundSource', 'imageNotFoundDestinationFileName', 'disableI18nProcessing', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(ImageFactoryTableMap::ID, ImageFactoryTableMap::CODE, ImageFactoryTableMap::SOURCES, ImageFactoryTableMap::DESTINATION, ImageFactoryTableMap::JUST_SYMLINK, ImageFactoryTableMap::WIDTH, ImageFactoryTableMap::HEIGHT, ImageFactoryTableMap::QUALITY, ImageFactoryTableMap::BACKGROUND_COLOR, ImageFactoryTableMap::BACKGROUND_OPACITY, ImageFactoryTableMap::RESIZE_MODE, ImageFactoryTableMap::ROTATION, ImageFactoryTableMap::RESAMPLING_FILTER, ImageFactoryTableMap::PREFIX, ImageFactoryTableMap::SUFFIX, ImageFactoryTableMap::LAYERS, ImageFactoryTableMap::EFFECTS, ImageFactoryTableMap::PIXEL_RATIOS, ImageFactoryTableMap::INTERLACE, ImageFactoryTableMap::PERSIST, ImageFactoryTableMap::ALLOW_ZOOM, ImageFactoryTableMap::IMAGINE_LIBRARY_CODE, ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE, ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME, ImageFactoryTableMap::DISABLE_I18N_PROCESSING, ImageFactoryTableMap::CREATED_AT, ImageFactoryTableMap::UPDATED_AT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'CODE', 'SOURCES', 'DESTINATION', 'JUST_SYMLINK', 'WIDTH', 'HEIGHT', 'QUALITY', 'BACKGROUND_COLOR', 'BACKGROUND_OPACITY', 'RESIZE_MODE', 'ROTATION', 'RESAMPLING_FILTER', 'PREFIX', 'SUFFIX', 'LAYERS', 'EFFECTS', 'PIXEL_RATIOS', 'INTERLACE', 'PERSIST', 'ALLOW_ZOOM', 'IMAGINE_LIBRARY_CODE', 'IMAGE_NOT_FOUND_SOURCE', 'IMAGE_NOT_FOUND_DESTINATION_FILE_NAME', 'DISABLE_I18N_PROCESSING', 'CREATED_AT', 'UPDATED_AT', ),
        self::TYPE_FIELDNAME     => array('id', 'code', 'sources', 'destination', 'just_symlink', 'width', 'height', 'quality', 'background_color', 'background_opacity', 'resize_mode', 'rotation', 'resampling_filter', 'prefix', 'suffix', 'layers', 'effects', 'pixel_ratios', 'interlace', 'persist', 'allow_zoom', 'imagine_library_code', 'image_not_found_source', 'image_not_found_destination_file_name', 'disable_i18n_processing', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Code' => 1, 'Sources' => 2, 'Destination' => 3, 'JustSymlink' => 4, 'Width' => 5, 'Height' => 6, 'Quality' => 7, 'BackgroundColor' => 8, 'BackgroundOpacity' => 9, 'ResizeMode' => 10, 'Rotation' => 11, 'ResamplingFilter' => 12, 'Prefix' => 13, 'Suffix' => 14, 'Layers' => 15, 'Effects' => 16, 'PixelRatios' => 17, 'Interlace' => 18, 'Persist' => 19, 'AllowZoom' => 20, 'ImagineLibraryCode' => 21, 'ImageNotFoundSource' => 22, 'ImageNotFoundDestinationFileName' => 23, 'DisableI18nProcessing' => 24, 'CreatedAt' => 25, 'UpdatedAt' => 26, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'code' => 1, 'sources' => 2, 'destination' => 3, 'justSymlink' => 4, 'width' => 5, 'height' => 6, 'quality' => 7, 'backgroundColor' => 8, 'backgroundOpacity' => 9, 'resizeMode' => 10, 'rotation' => 11, 'resamplingFilter' => 12, 'prefix' => 13, 'suffix' => 14, 'layers' => 15, 'effects' => 16, 'pixelRatios' => 17, 'interlace' => 18, 'persist' => 19, 'allowZoom' => 20, 'imagineLibraryCode' => 21, 'imageNotFoundSource' => 22, 'imageNotFoundDestinationFileName' => 23, 'disableI18nProcessing' => 24, 'createdAt' => 25, 'updatedAt' => 26, ),
        self::TYPE_COLNAME       => array(ImageFactoryTableMap::ID => 0, ImageFactoryTableMap::CODE => 1, ImageFactoryTableMap::SOURCES => 2, ImageFactoryTableMap::DESTINATION => 3, ImageFactoryTableMap::JUST_SYMLINK => 4, ImageFactoryTableMap::WIDTH => 5, ImageFactoryTableMap::HEIGHT => 6, ImageFactoryTableMap::QUALITY => 7, ImageFactoryTableMap::BACKGROUND_COLOR => 8, ImageFactoryTableMap::BACKGROUND_OPACITY => 9, ImageFactoryTableMap::RESIZE_MODE => 10, ImageFactoryTableMap::ROTATION => 11, ImageFactoryTableMap::RESAMPLING_FILTER => 12, ImageFactoryTableMap::PREFIX => 13, ImageFactoryTableMap::SUFFIX => 14, ImageFactoryTableMap::LAYERS => 15, ImageFactoryTableMap::EFFECTS => 16, ImageFactoryTableMap::PIXEL_RATIOS => 17, ImageFactoryTableMap::INTERLACE => 18, ImageFactoryTableMap::PERSIST => 19, ImageFactoryTableMap::ALLOW_ZOOM => 20, ImageFactoryTableMap::IMAGINE_LIBRARY_CODE => 21, ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE => 22, ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME => 23, ImageFactoryTableMap::DISABLE_I18N_PROCESSING => 24, ImageFactoryTableMap::CREATED_AT => 25, ImageFactoryTableMap::UPDATED_AT => 26, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'CODE' => 1, 'SOURCES' => 2, 'DESTINATION' => 3, 'JUST_SYMLINK' => 4, 'WIDTH' => 5, 'HEIGHT' => 6, 'QUALITY' => 7, 'BACKGROUND_COLOR' => 8, 'BACKGROUND_OPACITY' => 9, 'RESIZE_MODE' => 10, 'ROTATION' => 11, 'RESAMPLING_FILTER' => 12, 'PREFIX' => 13, 'SUFFIX' => 14, 'LAYERS' => 15, 'EFFECTS' => 16, 'PIXEL_RATIOS' => 17, 'INTERLACE' => 18, 'PERSIST' => 19, 'ALLOW_ZOOM' => 20, 'IMAGINE_LIBRARY_CODE' => 21, 'IMAGE_NOT_FOUND_SOURCE' => 22, 'IMAGE_NOT_FOUND_DESTINATION_FILE_NAME' => 23, 'DISABLE_I18N_PROCESSING' => 24, 'CREATED_AT' => 25, 'UPDATED_AT' => 26, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'code' => 1, 'sources' => 2, 'destination' => 3, 'just_symlink' => 4, 'width' => 5, 'height' => 6, 'quality' => 7, 'background_color' => 8, 'background_opacity' => 9, 'resize_mode' => 10, 'rotation' => 11, 'resampling_filter' => 12, 'prefix' => 13, 'suffix' => 14, 'layers' => 15, 'effects' => 16, 'pixel_ratios' => 17, 'interlace' => 18, 'persist' => 19, 'allow_zoom' => 20, 'imagine_library_code' => 21, 'image_not_found_source' => 22, 'image_not_found_destination_file_name' => 23, 'disable_i18n_processing' => 24, 'created_at' => 25, 'updated_at' => 26, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('image_factory');
        $this->setPhpName('ImageFactory');
        $this->setClassName('\\ImageFactory\\Model\\ImageFactory');
        $this->setPackage('ImageFactory.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('CODE', 'Code', 'VARCHAR', true, 255, null);
        $this->addColumn('SOURCES', 'Sources', 'ARRAY', true, null, null);
        $this->addColumn('DESTINATION', 'Destination', 'VARCHAR', false, 255, null);
        $this->addColumn('JUST_SYMLINK', 'JustSymlink', 'TINYINT', false, null, 0);
        $this->addColumn('WIDTH', 'Width', 'INTEGER', true, null, null);
        $this->addColumn('HEIGHT', 'Height', 'INTEGER', true, null, null);
        $this->addColumn('QUALITY', 'Quality', 'TINYINT', true, null, 75);
        $this->addColumn('BACKGROUND_COLOR', 'BackgroundColor', 'CHAR', false, 6, 'FFFFFF');
        $this->addColumn('BACKGROUND_OPACITY', 'BackgroundOpacity', 'TINYINT', false, null, 100);
        $this->addColumn('RESIZE_MODE', 'ResizeMode', 'VARCHAR', false, 55, 'exact_ratio_with_borders');
        $this->addColumn('ROTATION', 'Rotation', 'TINYINT', false, null, 0);
        $this->addColumn('RESAMPLING_FILTER', 'ResamplingFilter', 'VARCHAR', false, 55, 'undefined');
        $this->addColumn('PREFIX', 'Prefix', 'VARCHAR', false, 55, null);
        $this->addColumn('SUFFIX', 'Suffix', 'VARCHAR', false, 55, null);
        $this->addColumn('LAYERS', 'Layers', 'ARRAY', false, null, null);
        $this->addColumn('EFFECTS', 'Effects', 'ARRAY', false, null, null);
        $this->addColumn('PIXEL_RATIOS', 'PixelRatios', 'ARRAY', false, null, null);
        $this->addColumn('INTERLACE', 'Interlace', 'VARCHAR', false, 55, 'none');
        $this->addColumn('PERSIST', 'Persist', 'BOOLEAN', false, 1, true);
        $this->addColumn('ALLOW_ZOOM', 'AllowZoom', 'BOOLEAN', false, 1, false);
        $this->addColumn('IMAGINE_LIBRARY_CODE', 'ImagineLibraryCode', 'VARCHAR', false, 255, 'gd');
        $this->addColumn('IMAGE_NOT_FOUND_SOURCE', 'ImageNotFoundSource', 'VARCHAR', false, 255, null);
        $this->addColumn('IMAGE_NOT_FOUND_DESTINATION_FILE_NAME', 'ImageNotFoundDestinationFileName', 'VARCHAR', false, 255, null);
        $this->addColumn('DISABLE_I18N_PROCESSING', 'DisableI18nProcessing', 'TINYINT', false, null, 0);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ImageFactoryI18n', '\\ImageFactory\\Model\\ImageFactoryI18n', RelationMap::ONE_TO_MANY, array('id' => 'id', ), 'CASCADE', null, 'ImageFactoryI18ns');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
            'i18n' => array('i18n_table' => '%TABLE%_i18n', 'i18n_phpname' => '%PHPNAME%I18n', 'i18n_columns' => 'title, description', 'locale_column' => 'locale', 'locale_length' => '5', 'default_locale' => '', 'locale_alias' => '', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to image_factory     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in ".$this->getClassNameFromBuilder($joinedTableTableMapBuilder)." instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
                ImageFactoryI18nTableMap::clearInstancePool();
            }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ImageFactoryTableMap::CLASS_DEFAULT : ImageFactoryTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (ImageFactory object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ImageFactoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ImageFactoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ImageFactoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ImageFactoryTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ImageFactoryTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ImageFactoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ImageFactoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ImageFactoryTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ImageFactoryTableMap::ID);
            $criteria->addSelectColumn(ImageFactoryTableMap::CODE);
            $criteria->addSelectColumn(ImageFactoryTableMap::SOURCES);
            $criteria->addSelectColumn(ImageFactoryTableMap::DESTINATION);
            $criteria->addSelectColumn(ImageFactoryTableMap::JUST_SYMLINK);
            $criteria->addSelectColumn(ImageFactoryTableMap::WIDTH);
            $criteria->addSelectColumn(ImageFactoryTableMap::HEIGHT);
            $criteria->addSelectColumn(ImageFactoryTableMap::QUALITY);
            $criteria->addSelectColumn(ImageFactoryTableMap::BACKGROUND_COLOR);
            $criteria->addSelectColumn(ImageFactoryTableMap::BACKGROUND_OPACITY);
            $criteria->addSelectColumn(ImageFactoryTableMap::RESIZE_MODE);
            $criteria->addSelectColumn(ImageFactoryTableMap::ROTATION);
            $criteria->addSelectColumn(ImageFactoryTableMap::RESAMPLING_FILTER);
            $criteria->addSelectColumn(ImageFactoryTableMap::PREFIX);
            $criteria->addSelectColumn(ImageFactoryTableMap::SUFFIX);
            $criteria->addSelectColumn(ImageFactoryTableMap::LAYERS);
            $criteria->addSelectColumn(ImageFactoryTableMap::EFFECTS);
            $criteria->addSelectColumn(ImageFactoryTableMap::PIXEL_RATIOS);
            $criteria->addSelectColumn(ImageFactoryTableMap::INTERLACE);
            $criteria->addSelectColumn(ImageFactoryTableMap::PERSIST);
            $criteria->addSelectColumn(ImageFactoryTableMap::ALLOW_ZOOM);
            $criteria->addSelectColumn(ImageFactoryTableMap::IMAGINE_LIBRARY_CODE);
            $criteria->addSelectColumn(ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE);
            $criteria->addSelectColumn(ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME);
            $criteria->addSelectColumn(ImageFactoryTableMap::DISABLE_I18N_PROCESSING);
            $criteria->addSelectColumn(ImageFactoryTableMap::CREATED_AT);
            $criteria->addSelectColumn(ImageFactoryTableMap::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.CODE');
            $criteria->addSelectColumn($alias . '.SOURCES');
            $criteria->addSelectColumn($alias . '.DESTINATION');
            $criteria->addSelectColumn($alias . '.JUST_SYMLINK');
            $criteria->addSelectColumn($alias . '.WIDTH');
            $criteria->addSelectColumn($alias . '.HEIGHT');
            $criteria->addSelectColumn($alias . '.QUALITY');
            $criteria->addSelectColumn($alias . '.BACKGROUND_COLOR');
            $criteria->addSelectColumn($alias . '.BACKGROUND_OPACITY');
            $criteria->addSelectColumn($alias . '.RESIZE_MODE');
            $criteria->addSelectColumn($alias . '.ROTATION');
            $criteria->addSelectColumn($alias . '.RESAMPLING_FILTER');
            $criteria->addSelectColumn($alias . '.PREFIX');
            $criteria->addSelectColumn($alias . '.SUFFIX');
            $criteria->addSelectColumn($alias . '.LAYERS');
            $criteria->addSelectColumn($alias . '.EFFECTS');
            $criteria->addSelectColumn($alias . '.PIXEL_RATIOS');
            $criteria->addSelectColumn($alias . '.INTERLACE');
            $criteria->addSelectColumn($alias . '.PERSIST');
            $criteria->addSelectColumn($alias . '.ALLOW_ZOOM');
            $criteria->addSelectColumn($alias . '.IMAGINE_LIBRARY_CODE');
            $criteria->addSelectColumn($alias . '.IMAGE_NOT_FOUND_SOURCE');
            $criteria->addSelectColumn($alias . '.IMAGE_NOT_FOUND_DESTINATION_FILE_NAME');
            $criteria->addSelectColumn($alias . '.DISABLE_I18N_PROCESSING');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ImageFactoryTableMap::DATABASE_NAME)->getTable(ImageFactoryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(ImageFactoryTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(ImageFactoryTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new ImageFactoryTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a ImageFactory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ImageFactory object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageFactoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ImageFactory\Model\ImageFactory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ImageFactoryTableMap::DATABASE_NAME);
            $criteria->add(ImageFactoryTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = ImageFactoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { ImageFactoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { ImageFactoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the image_factory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ImageFactoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ImageFactory or Criteria object.
     *
     * @param mixed               $criteria Criteria or ImageFactory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageFactoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ImageFactory object
        }

        if ($criteria->containsKey(ImageFactoryTableMap::ID) && $criteria->keyContainsValue(ImageFactoryTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ImageFactoryTableMap::ID.')');
        }


        // Set the correct dbName
        $query = ImageFactoryQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // ImageFactoryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ImageFactoryTableMap::buildTableMap();
