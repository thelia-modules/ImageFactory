<?php

namespace ImageFactory\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use ImageFactory\Model\ImageFactory as ChildImageFactory;
use ImageFactory\Model\ImageFactoryI18n as ChildImageFactoryI18n;
use ImageFactory\Model\ImageFactoryI18nQuery as ChildImageFactoryI18nQuery;
use ImageFactory\Model\ImageFactoryQuery as ChildImageFactoryQuery;
use ImageFactory\Model\Map\ImageFactoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

abstract class ImageFactory implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\ImageFactory\\Model\\Map\\ImageFactoryTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the sources field.
     * @var        array
     */
    protected $sources;

    /**
     * The unserialized $sources value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $sources_unserialized;

    /**
     * The value for the destination field.
     * @var        string
     */
    protected $destination;

    /**
     * The value for the width field.
     * @var        int
     */
    protected $width;

    /**
     * The value for the height field.
     * @var        int
     */
    protected $height;

    /**
     * The value for the quality field.
     * Note: this column has a database default value of: 75
     * @var        int
     */
    protected $quality;

    /**
     * The value for the background_color field.
     * Note: this column has a database default value of: 'FFFFFF'
     * @var        string
     */
    protected $background_color;

    /**
     * The value for the background_opacity field.
     * Note: this column has a database default value of: 100
     * @var        int
     */
    protected $background_opacity;

    /**
     * The value for the resize_mode field.
     * Note: this column has a database default value of: 'exact_ratio_with_borders'
     * @var        string
     */
    protected $resize_mode;

    /**
     * The value for the rotation field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $rotation;

    /**
     * The value for the prefix field.
     * @var        string
     */
    protected $prefix;

    /**
     * The value for the suffix field.
     * @var        string
     */
    protected $suffix;

    /**
     * The value for the layers field.
     * @var        array
     */
    protected $layers;

    /**
     * The unserialized $layers value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $layers_unserialized;

    /**
     * The value for the effects field.
     * @var        array
     */
    protected $effects;

    /**
     * The unserialized $effects value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $effects_unserialized;

    /**
     * The value for the pixel_ratios field.
     * @var        array
     */
    protected $pixel_ratios;

    /**
     * The unserialized $pixel_ratios value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $pixel_ratios_unserialized;

    /**
     * The value for the interlace field.
     * Note: this column has a database default value of: 'none'
     * @var        string
     */
    protected $interlace;

    /**
     * The value for the persist field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $persist;

    /**
     * The value for the imagine_library_code field.
     * Note: this column has a database default value of: 'gd'
     * @var        string
     */
    protected $imagine_library_code;

    /**
     * The value for the image_not_found_source field.
     * @var        string
     */
    protected $image_not_found_source;

    /**
     * The value for the image_not_found_destination_file_name field.
     * @var        string
     */
    protected $image_not_found_destination_file_name;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildImageFactoryI18n[] Collection to store aggregation of ChildImageFactoryI18n objects.
     */
    protected $collImageFactoryI18ns;
    protected $collImageFactoryI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[ChildImageFactoryI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $imageFactoryI18nsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->quality = 75;
        $this->background_color = 'FFFFFF';
        $this->background_opacity = 100;
        $this->resize_mode = 'exact_ratio_with_borders';
        $this->rotation = 0;
        $this->interlace = 'none';
        $this->persist = true;
        $this->imagine_library_code = 'gd';
    }

    /**
     * Initializes internal state of ImageFactory\Model\Base\ImageFactory object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>ImageFactory</code> instance.  If
     * <code>obj</code> is an instance of <code>ImageFactory</code>, delegates to
     * <code>equals(ImageFactory)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return ImageFactory The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return ImageFactory The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [sources] column value.
     *
     * @return   array
     */
    public function getSources()
    {
        if (null === $this->sources_unserialized) {
            $this->sources_unserialized = array();
        }
        if (!$this->sources_unserialized && null !== $this->sources) {
            $sources_unserialized = substr($this->sources, 2, -2);
            $this->sources_unserialized = $sources_unserialized ? explode(' | ', $sources_unserialized) : array();
        }

        return $this->sources_unserialized;
    }

    /**
     * Test the presence of a value in the [sources] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasSource($value)
    {
        return in_array($value, $this->getSources());
    } // hasSource()

    /**
     * Get the [destination] column value.
     *
     * @return   string
     */
    public function getDestination()
    {

        return $this->destination;
    }

    /**
     * Get the [width] column value.
     *
     * @return   int
     */
    public function getWidth()
    {

        return $this->width;
    }

    /**
     * Get the [height] column value.
     *
     * @return   int
     */
    public function getHeight()
    {

        return $this->height;
    }

    /**
     * Get the [quality] column value.
     *
     * @return   int
     */
    public function getQuality()
    {

        return $this->quality;
    }

    /**
     * Get the [background_color] column value.
     *
     * @return   string
     */
    public function getBackgroundColor()
    {

        return $this->background_color;
    }

    /**
     * Get the [background_opacity] column value.
     *
     * @return   int
     */
    public function getBackgroundOpacity()
    {

        return $this->background_opacity;
    }

    /**
     * Get the [resize_mode] column value.
     *
     * @return   string
     */
    public function getResizeMode()
    {

        return $this->resize_mode;
    }

    /**
     * Get the [rotation] column value.
     *
     * @return   int
     */
    public function getRotation()
    {

        return $this->rotation;
    }

    /**
     * Get the [prefix] column value.
     *
     * @return   string
     */
    public function getPrefix()
    {

        return $this->prefix;
    }

    /**
     * Get the [suffix] column value.
     *
     * @return   string
     */
    public function getSuffix()
    {

        return $this->suffix;
    }

    /**
     * Get the [layers] column value.
     *
     * @return   array
     */
    public function getLayers()
    {
        if (null === $this->layers_unserialized) {
            $this->layers_unserialized = array();
        }
        if (!$this->layers_unserialized && null !== $this->layers) {
            $layers_unserialized = substr($this->layers, 2, -2);
            $this->layers_unserialized = $layers_unserialized ? explode(' | ', $layers_unserialized) : array();
        }

        return $this->layers_unserialized;
    }

    /**
     * Test the presence of a value in the [layers] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasLayer($value)
    {
        return in_array($value, $this->getLayers());
    } // hasLayer()

    /**
     * Get the [effects] column value.
     *
     * @return   array
     */
    public function getEffects()
    {
        if (null === $this->effects_unserialized) {
            $this->effects_unserialized = array();
        }
        if (!$this->effects_unserialized && null !== $this->effects) {
            $effects_unserialized = substr($this->effects, 2, -2);
            $this->effects_unserialized = $effects_unserialized ? explode(' | ', $effects_unserialized) : array();
        }

        return $this->effects_unserialized;
    }

    /**
     * Test the presence of a value in the [effects] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasEffect($value)
    {
        return in_array($value, $this->getEffects());
    } // hasEffect()

    /**
     * Get the [pixel_ratios] column value.
     *
     * @return   array
     */
    public function getPixelRatios()
    {
        if (null === $this->pixel_ratios_unserialized) {
            $this->pixel_ratios_unserialized = array();
        }
        if (!$this->pixel_ratios_unserialized && null !== $this->pixel_ratios) {
            $pixel_ratios_unserialized = substr($this->pixel_ratios, 2, -2);
            $this->pixel_ratios_unserialized = $pixel_ratios_unserialized ? explode(' | ', $pixel_ratios_unserialized) : array();
        }

        return $this->pixel_ratios_unserialized;
    }

    /**
     * Test the presence of a value in the [pixel_ratios] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPixelRatio($value)
    {
        return in_array($value, $this->getPixelRatios());
    } // hasPixelRatio()

    /**
     * Get the [interlace] column value.
     *
     * @return   string
     */
    public function getInterlace()
    {

        return $this->interlace;
    }

    /**
     * Get the [persist] column value.
     *
     * @return   boolean
     */
    public function getPersist()
    {

        return $this->persist;
    }

    /**
     * Get the [imagine_library_code] column value.
     *
     * @return   string
     */
    public function getImagineLibraryCode()
    {

        return $this->imagine_library_code;
    }

    /**
     * Get the [image_not_found_source] column value.
     *
     * @return   string
     */
    public function getImageNotFoundSource()
    {

        return $this->image_not_found_source;
    }

    /**
     * Get the [image_not_found_destination_file_name] column value.
     *
     * @return   string
     */
    public function getImageNotFoundDestinationFileName()
    {

        return $this->image_not_found_destination_file_name;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ImageFactoryTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[ImageFactoryTableMap::CODE] = true;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [sources] column.
     *
     * @param      array $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setSources($v)
    {
        if ($this->sources_unserialized !== $v) {
            $this->sources_unserialized = $v;
            $this->sources = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[ImageFactoryTableMap::SOURCES] = true;
        }


        return $this;
    } // setSources()

    /**
     * Adds a value to the [sources] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function addSource($value)
    {
        $currentArray = $this->getSources();
        $currentArray []= $value;
        $this->setSources($currentArray);

        return $this;
    } // addSource()

    /**
     * Removes a value from the [sources] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function removeSource($value)
    {
        $targetArray = array();
        foreach ($this->getSources() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setSources($targetArray);

        return $this;
    } // removeSource()

    /**
     * Set the value of [destination] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setDestination($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->destination !== $v) {
            $this->destination = $v;
            $this->modifiedColumns[ImageFactoryTableMap::DESTINATION] = true;
        }


        return $this;
    } // setDestination()

    /**
     * Set the value of [width] column.
     *
     * @param      int $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setWidth($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->width !== $v) {
            $this->width = $v;
            $this->modifiedColumns[ImageFactoryTableMap::WIDTH] = true;
        }


        return $this;
    } // setWidth()

    /**
     * Set the value of [height] column.
     *
     * @param      int $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setHeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->height !== $v) {
            $this->height = $v;
            $this->modifiedColumns[ImageFactoryTableMap::HEIGHT] = true;
        }


        return $this;
    } // setHeight()

    /**
     * Set the value of [quality] column.
     *
     * @param      int $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setQuality($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->quality !== $v) {
            $this->quality = $v;
            $this->modifiedColumns[ImageFactoryTableMap::QUALITY] = true;
        }


        return $this;
    } // setQuality()

    /**
     * Set the value of [background_color] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setBackgroundColor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->background_color !== $v) {
            $this->background_color = $v;
            $this->modifiedColumns[ImageFactoryTableMap::BACKGROUND_COLOR] = true;
        }


        return $this;
    } // setBackgroundColor()

    /**
     * Set the value of [background_opacity] column.
     *
     * @param      int $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setBackgroundOpacity($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->background_opacity !== $v) {
            $this->background_opacity = $v;
            $this->modifiedColumns[ImageFactoryTableMap::BACKGROUND_OPACITY] = true;
        }


        return $this;
    } // setBackgroundOpacity()

    /**
     * Set the value of [resize_mode] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setResizeMode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->resize_mode !== $v) {
            $this->resize_mode = $v;
            $this->modifiedColumns[ImageFactoryTableMap::RESIZE_MODE] = true;
        }


        return $this;
    } // setResizeMode()

    /**
     * Set the value of [rotation] column.
     *
     * @param      int $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setRotation($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->rotation !== $v) {
            $this->rotation = $v;
            $this->modifiedColumns[ImageFactoryTableMap::ROTATION] = true;
        }


        return $this;
    } // setRotation()

    /**
     * Set the value of [prefix] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setPrefix($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->prefix !== $v) {
            $this->prefix = $v;
            $this->modifiedColumns[ImageFactoryTableMap::PREFIX] = true;
        }


        return $this;
    } // setPrefix()

    /**
     * Set the value of [suffix] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setSuffix($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->suffix !== $v) {
            $this->suffix = $v;
            $this->modifiedColumns[ImageFactoryTableMap::SUFFIX] = true;
        }


        return $this;
    } // setSuffix()

    /**
     * Set the value of [layers] column.
     *
     * @param      array $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setLayers($v)
    {
        if ($this->layers_unserialized !== $v) {
            $this->layers_unserialized = $v;
            $this->layers = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[ImageFactoryTableMap::LAYERS] = true;
        }


        return $this;
    } // setLayers()

    /**
     * Adds a value to the [layers] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function addLayer($value)
    {
        $currentArray = $this->getLayers();
        $currentArray []= $value;
        $this->setLayers($currentArray);

        return $this;
    } // addLayer()

    /**
     * Removes a value from the [layers] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function removeLayer($value)
    {
        $targetArray = array();
        foreach ($this->getLayers() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setLayers($targetArray);

        return $this;
    } // removeLayer()

    /**
     * Set the value of [effects] column.
     *
     * @param      array $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setEffects($v)
    {
        if ($this->effects_unserialized !== $v) {
            $this->effects_unserialized = $v;
            $this->effects = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[ImageFactoryTableMap::EFFECTS] = true;
        }


        return $this;
    } // setEffects()

    /**
     * Adds a value to the [effects] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function addEffect($value)
    {
        $currentArray = $this->getEffects();
        $currentArray []= $value;
        $this->setEffects($currentArray);

        return $this;
    } // addEffect()

    /**
     * Removes a value from the [effects] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function removeEffect($value)
    {
        $targetArray = array();
        foreach ($this->getEffects() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setEffects($targetArray);

        return $this;
    } // removeEffect()

    /**
     * Set the value of [pixel_ratios] column.
     *
     * @param      array $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setPixelRatios($v)
    {
        if ($this->pixel_ratios_unserialized !== $v) {
            $this->pixel_ratios_unserialized = $v;
            $this->pixel_ratios = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[ImageFactoryTableMap::PIXEL_RATIOS] = true;
        }


        return $this;
    } // setPixelRatios()

    /**
     * Adds a value to the [pixel_ratios] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function addPixelRatio($value)
    {
        $currentArray = $this->getPixelRatios();
        $currentArray []= $value;
        $this->setPixelRatios($currentArray);

        return $this;
    } // addPixelRatio()

    /**
     * Removes a value from the [pixel_ratios] array column value.
     * @param      mixed $value
     *
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function removePixelRatio($value)
    {
        $targetArray = array();
        foreach ($this->getPixelRatios() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPixelRatios($targetArray);

        return $this;
    } // removePixelRatio()

    /**
     * Set the value of [interlace] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setInterlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->interlace !== $v) {
            $this->interlace = $v;
            $this->modifiedColumns[ImageFactoryTableMap::INTERLACE] = true;
        }


        return $this;
    } // setInterlace()

    /**
     * Sets the value of the [persist] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setPersist($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->persist !== $v) {
            $this->persist = $v;
            $this->modifiedColumns[ImageFactoryTableMap::PERSIST] = true;
        }


        return $this;
    } // setPersist()

    /**
     * Set the value of [imagine_library_code] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setImagineLibraryCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->imagine_library_code !== $v) {
            $this->imagine_library_code = $v;
            $this->modifiedColumns[ImageFactoryTableMap::IMAGINE_LIBRARY_CODE] = true;
        }


        return $this;
    } // setImagineLibraryCode()

    /**
     * Set the value of [image_not_found_source] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setImageNotFoundSource($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image_not_found_source !== $v) {
            $this->image_not_found_source = $v;
            $this->modifiedColumns[ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE] = true;
        }


        return $this;
    } // setImageNotFoundSource()

    /**
     * Set the value of [image_not_found_destination_file_name] column.
     *
     * @param      string $v new value
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setImageNotFoundDestinationFileName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image_not_found_destination_file_name !== $v) {
            $this->image_not_found_destination_file_name = $v;
            $this->modifiedColumns[ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME] = true;
        }


        return $this;
    } // setImageNotFoundDestinationFileName()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[ImageFactoryTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[ImageFactoryTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->quality !== 75) {
                return false;
            }

            if ($this->background_color !== 'FFFFFF') {
                return false;
            }

            if ($this->background_opacity !== 100) {
                return false;
            }

            if ($this->resize_mode !== 'exact_ratio_with_borders') {
                return false;
            }

            if ($this->rotation !== 0) {
                return false;
            }

            if ($this->interlace !== 'none') {
                return false;
            }

            if ($this->persist !== true) {
                return false;
            }

            if ($this->imagine_library_code !== 'gd') {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ImageFactoryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ImageFactoryTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ImageFactoryTableMap::translateFieldName('Sources', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sources = $col;
            $this->sources_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ImageFactoryTableMap::translateFieldName('Destination', TableMap::TYPE_PHPNAME, $indexType)];
            $this->destination = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ImageFactoryTableMap::translateFieldName('Width', TableMap::TYPE_PHPNAME, $indexType)];
            $this->width = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ImageFactoryTableMap::translateFieldName('Height', TableMap::TYPE_PHPNAME, $indexType)];
            $this->height = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ImageFactoryTableMap::translateFieldName('Quality', TableMap::TYPE_PHPNAME, $indexType)];
            $this->quality = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ImageFactoryTableMap::translateFieldName('BackgroundColor', TableMap::TYPE_PHPNAME, $indexType)];
            $this->background_color = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ImageFactoryTableMap::translateFieldName('BackgroundOpacity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->background_opacity = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ImageFactoryTableMap::translateFieldName('ResizeMode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->resize_mode = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ImageFactoryTableMap::translateFieldName('Rotation', TableMap::TYPE_PHPNAME, $indexType)];
            $this->rotation = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : ImageFactoryTableMap::translateFieldName('Prefix', TableMap::TYPE_PHPNAME, $indexType)];
            $this->prefix = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : ImageFactoryTableMap::translateFieldName('Suffix', TableMap::TYPE_PHPNAME, $indexType)];
            $this->suffix = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : ImageFactoryTableMap::translateFieldName('Layers', TableMap::TYPE_PHPNAME, $indexType)];
            $this->layers = $col;
            $this->layers_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : ImageFactoryTableMap::translateFieldName('Effects', TableMap::TYPE_PHPNAME, $indexType)];
            $this->effects = $col;
            $this->effects_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : ImageFactoryTableMap::translateFieldName('PixelRatios', TableMap::TYPE_PHPNAME, $indexType)];
            $this->pixel_ratios = $col;
            $this->pixel_ratios_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : ImageFactoryTableMap::translateFieldName('Interlace', TableMap::TYPE_PHPNAME, $indexType)];
            $this->interlace = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : ImageFactoryTableMap::translateFieldName('Persist', TableMap::TYPE_PHPNAME, $indexType)];
            $this->persist = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : ImageFactoryTableMap::translateFieldName('ImagineLibraryCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->imagine_library_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : ImageFactoryTableMap::translateFieldName('ImageNotFoundSource', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image_not_found_source = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : ImageFactoryTableMap::translateFieldName('ImageNotFoundDestinationFileName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image_not_found_destination_file_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : ImageFactoryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : ImageFactoryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 23; // 23 = ImageFactoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \ImageFactory\Model\ImageFactory object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ImageFactoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildImageFactoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collImageFactoryI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ImageFactory::setDeleted()
     * @see ImageFactory::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageFactoryTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildImageFactoryQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageFactoryTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(ImageFactoryTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(ImageFactoryTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ImageFactoryTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ImageFactoryTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->imageFactoryI18nsScheduledForDeletion !== null) {
                if (!$this->imageFactoryI18nsScheduledForDeletion->isEmpty()) {
                    \ImageFactory\Model\ImageFactoryI18nQuery::create()
                        ->filterByPrimaryKeys($this->imageFactoryI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->imageFactoryI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collImageFactoryI18ns !== null) {
            foreach ($this->collImageFactoryI18ns as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[ImageFactoryTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ImageFactoryTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ImageFactoryTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::SOURCES)) {
            $modifiedColumns[':p' . $index++]  = 'SOURCES';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::DESTINATION)) {
            $modifiedColumns[':p' . $index++]  = 'DESTINATION';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::WIDTH)) {
            $modifiedColumns[':p' . $index++]  = 'WIDTH';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::HEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'HEIGHT';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::QUALITY)) {
            $modifiedColumns[':p' . $index++]  = 'QUALITY';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::BACKGROUND_COLOR)) {
            $modifiedColumns[':p' . $index++]  = 'BACKGROUND_COLOR';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::BACKGROUND_OPACITY)) {
            $modifiedColumns[':p' . $index++]  = 'BACKGROUND_OPACITY';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::RESIZE_MODE)) {
            $modifiedColumns[':p' . $index++]  = 'RESIZE_MODE';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::ROTATION)) {
            $modifiedColumns[':p' . $index++]  = 'ROTATION';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::PREFIX)) {
            $modifiedColumns[':p' . $index++]  = 'PREFIX';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::SUFFIX)) {
            $modifiedColumns[':p' . $index++]  = 'SUFFIX';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::LAYERS)) {
            $modifiedColumns[':p' . $index++]  = 'LAYERS';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::EFFECTS)) {
            $modifiedColumns[':p' . $index++]  = 'EFFECTS';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::PIXEL_RATIOS)) {
            $modifiedColumns[':p' . $index++]  = 'PIXEL_RATIOS';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::INTERLACE)) {
            $modifiedColumns[':p' . $index++]  = 'INTERLACE';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::PERSIST)) {
            $modifiedColumns[':p' . $index++]  = 'PERSIST';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::IMAGINE_LIBRARY_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'IMAGINE_LIBRARY_CODE';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE)) {
            $modifiedColumns[':p' . $index++]  = 'IMAGE_NOT_FOUND_SOURCE';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'IMAGE_NOT_FOUND_DESTINATION_FILE_NAME';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(ImageFactoryTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO image_factory (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'SOURCES':
                        $stmt->bindValue($identifier, $this->sources, PDO::PARAM_STR);
                        break;
                    case 'DESTINATION':
                        $stmt->bindValue($identifier, $this->destination, PDO::PARAM_STR);
                        break;
                    case 'WIDTH':
                        $stmt->bindValue($identifier, $this->width, PDO::PARAM_INT);
                        break;
                    case 'HEIGHT':
                        $stmt->bindValue($identifier, $this->height, PDO::PARAM_INT);
                        break;
                    case 'QUALITY':
                        $stmt->bindValue($identifier, $this->quality, PDO::PARAM_INT);
                        break;
                    case 'BACKGROUND_COLOR':
                        $stmt->bindValue($identifier, $this->background_color, PDO::PARAM_STR);
                        break;
                    case 'BACKGROUND_OPACITY':
                        $stmt->bindValue($identifier, $this->background_opacity, PDO::PARAM_INT);
                        break;
                    case 'RESIZE_MODE':
                        $stmt->bindValue($identifier, $this->resize_mode, PDO::PARAM_STR);
                        break;
                    case 'ROTATION':
                        $stmt->bindValue($identifier, $this->rotation, PDO::PARAM_INT);
                        break;
                    case 'PREFIX':
                        $stmt->bindValue($identifier, $this->prefix, PDO::PARAM_STR);
                        break;
                    case 'SUFFIX':
                        $stmt->bindValue($identifier, $this->suffix, PDO::PARAM_STR);
                        break;
                    case 'LAYERS':
                        $stmt->bindValue($identifier, $this->layers, PDO::PARAM_STR);
                        break;
                    case 'EFFECTS':
                        $stmt->bindValue($identifier, $this->effects, PDO::PARAM_STR);
                        break;
                    case 'PIXEL_RATIOS':
                        $stmt->bindValue($identifier, $this->pixel_ratios, PDO::PARAM_STR);
                        break;
                    case 'INTERLACE':
                        $stmt->bindValue($identifier, $this->interlace, PDO::PARAM_STR);
                        break;
                    case 'PERSIST':
                        $stmt->bindValue($identifier, (int) $this->persist, PDO::PARAM_INT);
                        break;
                    case 'IMAGINE_LIBRARY_CODE':
                        $stmt->bindValue($identifier, $this->imagine_library_code, PDO::PARAM_STR);
                        break;
                    case 'IMAGE_NOT_FOUND_SOURCE':
                        $stmt->bindValue($identifier, $this->image_not_found_source, PDO::PARAM_STR);
                        break;
                    case 'IMAGE_NOT_FOUND_DESTINATION_FILE_NAME':
                        $stmt->bindValue($identifier, $this->image_not_found_destination_file_name, PDO::PARAM_STR);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ImageFactoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCode();
                break;
            case 2:
                return $this->getSources();
                break;
            case 3:
                return $this->getDestination();
                break;
            case 4:
                return $this->getWidth();
                break;
            case 5:
                return $this->getHeight();
                break;
            case 6:
                return $this->getQuality();
                break;
            case 7:
                return $this->getBackgroundColor();
                break;
            case 8:
                return $this->getBackgroundOpacity();
                break;
            case 9:
                return $this->getResizeMode();
                break;
            case 10:
                return $this->getRotation();
                break;
            case 11:
                return $this->getPrefix();
                break;
            case 12:
                return $this->getSuffix();
                break;
            case 13:
                return $this->getLayers();
                break;
            case 14:
                return $this->getEffects();
                break;
            case 15:
                return $this->getPixelRatios();
                break;
            case 16:
                return $this->getInterlace();
                break;
            case 17:
                return $this->getPersist();
                break;
            case 18:
                return $this->getImagineLibraryCode();
                break;
            case 19:
                return $this->getImageNotFoundSource();
                break;
            case 20:
                return $this->getImageNotFoundDestinationFileName();
                break;
            case 21:
                return $this->getCreatedAt();
                break;
            case 22:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['ImageFactory'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ImageFactory'][$this->getPrimaryKey()] = true;
        $keys = ImageFactoryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getSources(),
            $keys[3] => $this->getDestination(),
            $keys[4] => $this->getWidth(),
            $keys[5] => $this->getHeight(),
            $keys[6] => $this->getQuality(),
            $keys[7] => $this->getBackgroundColor(),
            $keys[8] => $this->getBackgroundOpacity(),
            $keys[9] => $this->getResizeMode(),
            $keys[10] => $this->getRotation(),
            $keys[11] => $this->getPrefix(),
            $keys[12] => $this->getSuffix(),
            $keys[13] => $this->getLayers(),
            $keys[14] => $this->getEffects(),
            $keys[15] => $this->getPixelRatios(),
            $keys[16] => $this->getInterlace(),
            $keys[17] => $this->getPersist(),
            $keys[18] => $this->getImagineLibraryCode(),
            $keys[19] => $this->getImageNotFoundSource(),
            $keys[20] => $this->getImageNotFoundDestinationFileName(),
            $keys[21] => $this->getCreatedAt(),
            $keys[22] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collImageFactoryI18ns) {
                $result['ImageFactoryI18ns'] = $this->collImageFactoryI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ImageFactoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCode($value);
                break;
            case 2:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setSources($value);
                break;
            case 3:
                $this->setDestination($value);
                break;
            case 4:
                $this->setWidth($value);
                break;
            case 5:
                $this->setHeight($value);
                break;
            case 6:
                $this->setQuality($value);
                break;
            case 7:
                $this->setBackgroundColor($value);
                break;
            case 8:
                $this->setBackgroundOpacity($value);
                break;
            case 9:
                $this->setResizeMode($value);
                break;
            case 10:
                $this->setRotation($value);
                break;
            case 11:
                $this->setPrefix($value);
                break;
            case 12:
                $this->setSuffix($value);
                break;
            case 13:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setLayers($value);
                break;
            case 14:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setEffects($value);
                break;
            case 15:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPixelRatios($value);
                break;
            case 16:
                $this->setInterlace($value);
                break;
            case 17:
                $this->setPersist($value);
                break;
            case 18:
                $this->setImagineLibraryCode($value);
                break;
            case 19:
                $this->setImageNotFoundSource($value);
                break;
            case 20:
                $this->setImageNotFoundDestinationFileName($value);
                break;
            case 21:
                $this->setCreatedAt($value);
                break;
            case 22:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ImageFactoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSources($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDestination($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setWidth($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setHeight($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setQuality($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setBackgroundColor($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setBackgroundOpacity($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setResizeMode($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setRotation($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPrefix($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setSuffix($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setLayers($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setEffects($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setPixelRatios($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setInterlace($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setPersist($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setImagineLibraryCode($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setImageNotFoundSource($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setImageNotFoundDestinationFileName($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setCreatedAt($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setUpdatedAt($arr[$keys[22]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ImageFactoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ImageFactoryTableMap::ID)) $criteria->add(ImageFactoryTableMap::ID, $this->id);
        if ($this->isColumnModified(ImageFactoryTableMap::CODE)) $criteria->add(ImageFactoryTableMap::CODE, $this->code);
        if ($this->isColumnModified(ImageFactoryTableMap::SOURCES)) $criteria->add(ImageFactoryTableMap::SOURCES, $this->sources);
        if ($this->isColumnModified(ImageFactoryTableMap::DESTINATION)) $criteria->add(ImageFactoryTableMap::DESTINATION, $this->destination);
        if ($this->isColumnModified(ImageFactoryTableMap::WIDTH)) $criteria->add(ImageFactoryTableMap::WIDTH, $this->width);
        if ($this->isColumnModified(ImageFactoryTableMap::HEIGHT)) $criteria->add(ImageFactoryTableMap::HEIGHT, $this->height);
        if ($this->isColumnModified(ImageFactoryTableMap::QUALITY)) $criteria->add(ImageFactoryTableMap::QUALITY, $this->quality);
        if ($this->isColumnModified(ImageFactoryTableMap::BACKGROUND_COLOR)) $criteria->add(ImageFactoryTableMap::BACKGROUND_COLOR, $this->background_color);
        if ($this->isColumnModified(ImageFactoryTableMap::BACKGROUND_OPACITY)) $criteria->add(ImageFactoryTableMap::BACKGROUND_OPACITY, $this->background_opacity);
        if ($this->isColumnModified(ImageFactoryTableMap::RESIZE_MODE)) $criteria->add(ImageFactoryTableMap::RESIZE_MODE, $this->resize_mode);
        if ($this->isColumnModified(ImageFactoryTableMap::ROTATION)) $criteria->add(ImageFactoryTableMap::ROTATION, $this->rotation);
        if ($this->isColumnModified(ImageFactoryTableMap::PREFIX)) $criteria->add(ImageFactoryTableMap::PREFIX, $this->prefix);
        if ($this->isColumnModified(ImageFactoryTableMap::SUFFIX)) $criteria->add(ImageFactoryTableMap::SUFFIX, $this->suffix);
        if ($this->isColumnModified(ImageFactoryTableMap::LAYERS)) $criteria->add(ImageFactoryTableMap::LAYERS, $this->layers);
        if ($this->isColumnModified(ImageFactoryTableMap::EFFECTS)) $criteria->add(ImageFactoryTableMap::EFFECTS, $this->effects);
        if ($this->isColumnModified(ImageFactoryTableMap::PIXEL_RATIOS)) $criteria->add(ImageFactoryTableMap::PIXEL_RATIOS, $this->pixel_ratios);
        if ($this->isColumnModified(ImageFactoryTableMap::INTERLACE)) $criteria->add(ImageFactoryTableMap::INTERLACE, $this->interlace);
        if ($this->isColumnModified(ImageFactoryTableMap::PERSIST)) $criteria->add(ImageFactoryTableMap::PERSIST, $this->persist);
        if ($this->isColumnModified(ImageFactoryTableMap::IMAGINE_LIBRARY_CODE)) $criteria->add(ImageFactoryTableMap::IMAGINE_LIBRARY_CODE, $this->imagine_library_code);
        if ($this->isColumnModified(ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE)) $criteria->add(ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE, $this->image_not_found_source);
        if ($this->isColumnModified(ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME)) $criteria->add(ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME, $this->image_not_found_destination_file_name);
        if ($this->isColumnModified(ImageFactoryTableMap::CREATED_AT)) $criteria->add(ImageFactoryTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(ImageFactoryTableMap::UPDATED_AT)) $criteria->add(ImageFactoryTableMap::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(ImageFactoryTableMap::DATABASE_NAME);
        $criteria->add(ImageFactoryTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \ImageFactory\Model\ImageFactory (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setSources($this->getSources());
        $copyObj->setDestination($this->getDestination());
        $copyObj->setWidth($this->getWidth());
        $copyObj->setHeight($this->getHeight());
        $copyObj->setQuality($this->getQuality());
        $copyObj->setBackgroundColor($this->getBackgroundColor());
        $copyObj->setBackgroundOpacity($this->getBackgroundOpacity());
        $copyObj->setResizeMode($this->getResizeMode());
        $copyObj->setRotation($this->getRotation());
        $copyObj->setPrefix($this->getPrefix());
        $copyObj->setSuffix($this->getSuffix());
        $copyObj->setLayers($this->getLayers());
        $copyObj->setEffects($this->getEffects());
        $copyObj->setPixelRatios($this->getPixelRatios());
        $copyObj->setInterlace($this->getInterlace());
        $copyObj->setPersist($this->getPersist());
        $copyObj->setImagineLibraryCode($this->getImagineLibraryCode());
        $copyObj->setImageNotFoundSource($this->getImageNotFoundSource());
        $copyObj->setImageNotFoundDestinationFileName($this->getImageNotFoundDestinationFileName());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getImageFactoryI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addImageFactoryI18n($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \ImageFactory\Model\ImageFactory Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ImageFactoryI18n' == $relationName) {
            return $this->initImageFactoryI18ns();
        }
    }

    /**
     * Clears out the collImageFactoryI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addImageFactoryI18ns()
     */
    public function clearImageFactoryI18ns()
    {
        $this->collImageFactoryI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collImageFactoryI18ns collection loaded partially.
     */
    public function resetPartialImageFactoryI18ns($v = true)
    {
        $this->collImageFactoryI18nsPartial = $v;
    }

    /**
     * Initializes the collImageFactoryI18ns collection.
     *
     * By default this just sets the collImageFactoryI18ns collection to an empty array (like clearcollImageFactoryI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initImageFactoryI18ns($overrideExisting = true)
    {
        if (null !== $this->collImageFactoryI18ns && !$overrideExisting) {
            return;
        }
        $this->collImageFactoryI18ns = new ObjectCollection();
        $this->collImageFactoryI18ns->setModel('\ImageFactory\Model\ImageFactoryI18n');
    }

    /**
     * Gets an array of ChildImageFactoryI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildImageFactory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildImageFactoryI18n[] List of ChildImageFactoryI18n objects
     * @throws PropelException
     */
    public function getImageFactoryI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collImageFactoryI18nsPartial && !$this->isNew();
        if (null === $this->collImageFactoryI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collImageFactoryI18ns) {
                // return empty collection
                $this->initImageFactoryI18ns();
            } else {
                $collImageFactoryI18ns = ChildImageFactoryI18nQuery::create(null, $criteria)
                    ->filterByImageFactory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collImageFactoryI18nsPartial && count($collImageFactoryI18ns)) {
                        $this->initImageFactoryI18ns(false);

                        foreach ($collImageFactoryI18ns as $obj) {
                            if (false == $this->collImageFactoryI18ns->contains($obj)) {
                                $this->collImageFactoryI18ns->append($obj);
                            }
                        }

                        $this->collImageFactoryI18nsPartial = true;
                    }

                    reset($collImageFactoryI18ns);

                    return $collImageFactoryI18ns;
                }

                if ($partial && $this->collImageFactoryI18ns) {
                    foreach ($this->collImageFactoryI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collImageFactoryI18ns[] = $obj;
                        }
                    }
                }

                $this->collImageFactoryI18ns = $collImageFactoryI18ns;
                $this->collImageFactoryI18nsPartial = false;
            }
        }

        return $this->collImageFactoryI18ns;
    }

    /**
     * Sets a collection of ImageFactoryI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $imageFactoryI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildImageFactory The current object (for fluent API support)
     */
    public function setImageFactoryI18ns(Collection $imageFactoryI18ns, ConnectionInterface $con = null)
    {
        $imageFactoryI18nsToDelete = $this->getImageFactoryI18ns(new Criteria(), $con)->diff($imageFactoryI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->imageFactoryI18nsScheduledForDeletion = clone $imageFactoryI18nsToDelete;

        foreach ($imageFactoryI18nsToDelete as $imageFactoryI18nRemoved) {
            $imageFactoryI18nRemoved->setImageFactory(null);
        }

        $this->collImageFactoryI18ns = null;
        foreach ($imageFactoryI18ns as $imageFactoryI18n) {
            $this->addImageFactoryI18n($imageFactoryI18n);
        }

        $this->collImageFactoryI18ns = $imageFactoryI18ns;
        $this->collImageFactoryI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ImageFactoryI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ImageFactoryI18n objects.
     * @throws PropelException
     */
    public function countImageFactoryI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collImageFactoryI18nsPartial && !$this->isNew();
        if (null === $this->collImageFactoryI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collImageFactoryI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getImageFactoryI18ns());
            }

            $query = ChildImageFactoryI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByImageFactory($this)
                ->count($con);
        }

        return count($this->collImageFactoryI18ns);
    }

    /**
     * Method called to associate a ChildImageFactoryI18n object to this object
     * through the ChildImageFactoryI18n foreign key attribute.
     *
     * @param    ChildImageFactoryI18n $l ChildImageFactoryI18n
     * @return   \ImageFactory\Model\ImageFactory The current object (for fluent API support)
     */
    public function addImageFactoryI18n(ChildImageFactoryI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collImageFactoryI18ns === null) {
            $this->initImageFactoryI18ns();
            $this->collImageFactoryI18nsPartial = true;
        }

        if (!in_array($l, $this->collImageFactoryI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddImageFactoryI18n($l);
        }

        return $this;
    }

    /**
     * @param ImageFactoryI18n $imageFactoryI18n The imageFactoryI18n object to add.
     */
    protected function doAddImageFactoryI18n($imageFactoryI18n)
    {
        $this->collImageFactoryI18ns[]= $imageFactoryI18n;
        $imageFactoryI18n->setImageFactory($this);
    }

    /**
     * @param  ImageFactoryI18n $imageFactoryI18n The imageFactoryI18n object to remove.
     * @return ChildImageFactory The current object (for fluent API support)
     */
    public function removeImageFactoryI18n($imageFactoryI18n)
    {
        if ($this->getImageFactoryI18ns()->contains($imageFactoryI18n)) {
            $this->collImageFactoryI18ns->remove($this->collImageFactoryI18ns->search($imageFactoryI18n));
            if (null === $this->imageFactoryI18nsScheduledForDeletion) {
                $this->imageFactoryI18nsScheduledForDeletion = clone $this->collImageFactoryI18ns;
                $this->imageFactoryI18nsScheduledForDeletion->clear();
            }
            $this->imageFactoryI18nsScheduledForDeletion[]= clone $imageFactoryI18n;
            $imageFactoryI18n->setImageFactory(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->sources = null;
        $this->sources_unserialized = null;
        $this->destination = null;
        $this->width = null;
        $this->height = null;
        $this->quality = null;
        $this->background_color = null;
        $this->background_opacity = null;
        $this->resize_mode = null;
        $this->rotation = null;
        $this->prefix = null;
        $this->suffix = null;
        $this->layers = null;
        $this->layers_unserialized = null;
        $this->effects = null;
        $this->effects_unserialized = null;
        $this->pixel_ratios = null;
        $this->pixel_ratios_unserialized = null;
        $this->interlace = null;
        $this->persist = null;
        $this->imagine_library_code = null;
        $this->image_not_found_source = null;
        $this->image_not_found_destination_file_name = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collImageFactoryI18ns) {
                foreach ($this->collImageFactoryI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collImageFactoryI18ns = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ImageFactoryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildImageFactory The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ImageFactoryTableMap::UPDATED_AT] = true;

        return $this;
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildImageFactory The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildImageFactoryI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collImageFactoryI18ns) {
                foreach ($this->collImageFactoryI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildImageFactoryI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildImageFactoryI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addImageFactoryI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildImageFactory The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildImageFactoryI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collImageFactoryI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collImageFactoryI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildImageFactoryI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [title] column value.
         *
         * @return   string
         */
        public function getTitle()
        {
        return $this->getCurrentTranslation()->getTitle();
    }


        /**
         * Set the value of [title] column.
         *
         * @param      string $v new value
         * @return   \ImageFactory\Model\ImageFactoryI18n The current object (for fluent API support)
         */
        public function setTitle($v)
        {    $this->getCurrentTranslation()->setTitle($v);

        return $this;
    }


        /**
         * Get the [description] column value.
         *
         * @return   string
         */
        public function getDescription()
        {
        return $this->getCurrentTranslation()->getDescription();
    }


        /**
         * Set the value of [description] column.
         *
         * @param      string $v new value
         * @return   \ImageFactory\Model\ImageFactoryI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
