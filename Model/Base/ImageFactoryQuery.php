<?php

namespace ImageFactory\Model\Base;

use \Exception;
use \PDO;
use ImageFactory\Model\ImageFactory as ChildImageFactory;
use ImageFactory\Model\ImageFactoryI18nQuery as ChildImageFactoryI18nQuery;
use ImageFactory\Model\ImageFactoryQuery as ChildImageFactoryQuery;
use ImageFactory\Model\Map\ImageFactoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'image_factory' table.
 *
 *
 *
 * @method     ChildImageFactoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildImageFactoryQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildImageFactoryQuery orderBySources($order = Criteria::ASC) Order by the sources column
 * @method     ChildImageFactoryQuery orderByDestination($order = Criteria::ASC) Order by the destination column
 * @method     ChildImageFactoryQuery orderByWidth($order = Criteria::ASC) Order by the width column
 * @method     ChildImageFactoryQuery orderByHeight($order = Criteria::ASC) Order by the height column
 * @method     ChildImageFactoryQuery orderByQuality($order = Criteria::ASC) Order by the quality column
 * @method     ChildImageFactoryQuery orderByBackgroundColor($order = Criteria::ASC) Order by the background_color column
 * @method     ChildImageFactoryQuery orderByBackgroundOpacity($order = Criteria::ASC) Order by the background_opacity column
 * @method     ChildImageFactoryQuery orderByResizeMode($order = Criteria::ASC) Order by the resize_mode column
 * @method     ChildImageFactoryQuery orderByRotation($order = Criteria::ASC) Order by the rotation column
 * @method     ChildImageFactoryQuery orderByResamplingFilter($order = Criteria::ASC) Order by the resampling_filter column
 * @method     ChildImageFactoryQuery orderByPrefix($order = Criteria::ASC) Order by the prefix column
 * @method     ChildImageFactoryQuery orderBySuffix($order = Criteria::ASC) Order by the suffix column
 * @method     ChildImageFactoryQuery orderByLayers($order = Criteria::ASC) Order by the layers column
 * @method     ChildImageFactoryQuery orderByEffects($order = Criteria::ASC) Order by the effects column
 * @method     ChildImageFactoryQuery orderByPixelRatios($order = Criteria::ASC) Order by the pixel_ratios column
 * @method     ChildImageFactoryQuery orderByInterlace($order = Criteria::ASC) Order by the interlace column
 * @method     ChildImageFactoryQuery orderByPersist($order = Criteria::ASC) Order by the persist column
 * @method     ChildImageFactoryQuery orderByImagineLibraryCode($order = Criteria::ASC) Order by the imagine_library_code column
 * @method     ChildImageFactoryQuery orderByImageNotFoundSource($order = Criteria::ASC) Order by the image_not_found_source column
 * @method     ChildImageFactoryQuery orderByImageNotFoundDestinationFileName($order = Criteria::ASC) Order by the image_not_found_destination_file_name column
 * @method     ChildImageFactoryQuery orderByDisableI18nProcessing($order = Criteria::ASC) Order by the disable_i18n_processing column
 * @method     ChildImageFactoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildImageFactoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildImageFactoryQuery groupById() Group by the id column
 * @method     ChildImageFactoryQuery groupByCode() Group by the code column
 * @method     ChildImageFactoryQuery groupBySources() Group by the sources column
 * @method     ChildImageFactoryQuery groupByDestination() Group by the destination column
 * @method     ChildImageFactoryQuery groupByWidth() Group by the width column
 * @method     ChildImageFactoryQuery groupByHeight() Group by the height column
 * @method     ChildImageFactoryQuery groupByQuality() Group by the quality column
 * @method     ChildImageFactoryQuery groupByBackgroundColor() Group by the background_color column
 * @method     ChildImageFactoryQuery groupByBackgroundOpacity() Group by the background_opacity column
 * @method     ChildImageFactoryQuery groupByResizeMode() Group by the resize_mode column
 * @method     ChildImageFactoryQuery groupByRotation() Group by the rotation column
 * @method     ChildImageFactoryQuery groupByResamplingFilter() Group by the resampling_filter column
 * @method     ChildImageFactoryQuery groupByPrefix() Group by the prefix column
 * @method     ChildImageFactoryQuery groupBySuffix() Group by the suffix column
 * @method     ChildImageFactoryQuery groupByLayers() Group by the layers column
 * @method     ChildImageFactoryQuery groupByEffects() Group by the effects column
 * @method     ChildImageFactoryQuery groupByPixelRatios() Group by the pixel_ratios column
 * @method     ChildImageFactoryQuery groupByInterlace() Group by the interlace column
 * @method     ChildImageFactoryQuery groupByPersist() Group by the persist column
 * @method     ChildImageFactoryQuery groupByImagineLibraryCode() Group by the imagine_library_code column
 * @method     ChildImageFactoryQuery groupByImageNotFoundSource() Group by the image_not_found_source column
 * @method     ChildImageFactoryQuery groupByImageNotFoundDestinationFileName() Group by the image_not_found_destination_file_name column
 * @method     ChildImageFactoryQuery groupByDisableI18nProcessing() Group by the disable_i18n_processing column
 * @method     ChildImageFactoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildImageFactoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildImageFactoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildImageFactoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildImageFactoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildImageFactoryQuery leftJoinImageFactoryI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the ImageFactoryI18n relation
 * @method     ChildImageFactoryQuery rightJoinImageFactoryI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ImageFactoryI18n relation
 * @method     ChildImageFactoryQuery innerJoinImageFactoryI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the ImageFactoryI18n relation
 *
 * @method     ChildImageFactory findOne(ConnectionInterface $con = null) Return the first ChildImageFactory matching the query
 * @method     ChildImageFactory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildImageFactory matching the query, or a new ChildImageFactory object populated from the query conditions when no match is found
 *
 * @method     ChildImageFactory findOneById(int $id) Return the first ChildImageFactory filtered by the id column
 * @method     ChildImageFactory findOneByCode(string $code) Return the first ChildImageFactory filtered by the code column
 * @method     ChildImageFactory findOneBySources(array $sources) Return the first ChildImageFactory filtered by the sources column
 * @method     ChildImageFactory findOneByDestination(string $destination) Return the first ChildImageFactory filtered by the destination column
 * @method     ChildImageFactory findOneByWidth(int $width) Return the first ChildImageFactory filtered by the width column
 * @method     ChildImageFactory findOneByHeight(int $height) Return the first ChildImageFactory filtered by the height column
 * @method     ChildImageFactory findOneByQuality(int $quality) Return the first ChildImageFactory filtered by the quality column
 * @method     ChildImageFactory findOneByBackgroundColor(string $background_color) Return the first ChildImageFactory filtered by the background_color column
 * @method     ChildImageFactory findOneByBackgroundOpacity(int $background_opacity) Return the first ChildImageFactory filtered by the background_opacity column
 * @method     ChildImageFactory findOneByResizeMode(string $resize_mode) Return the first ChildImageFactory filtered by the resize_mode column
 * @method     ChildImageFactory findOneByRotation(int $rotation) Return the first ChildImageFactory filtered by the rotation column
 * @method     ChildImageFactory findOneByResamplingFilter(string $resampling_filter) Return the first ChildImageFactory filtered by the resampling_filter column
 * @method     ChildImageFactory findOneByPrefix(string $prefix) Return the first ChildImageFactory filtered by the prefix column
 * @method     ChildImageFactory findOneBySuffix(string $suffix) Return the first ChildImageFactory filtered by the suffix column
 * @method     ChildImageFactory findOneByLayers(array $layers) Return the first ChildImageFactory filtered by the layers column
 * @method     ChildImageFactory findOneByEffects(array $effects) Return the first ChildImageFactory filtered by the effects column
 * @method     ChildImageFactory findOneByPixelRatios(array $pixel_ratios) Return the first ChildImageFactory filtered by the pixel_ratios column
 * @method     ChildImageFactory findOneByInterlace(string $interlace) Return the first ChildImageFactory filtered by the interlace column
 * @method     ChildImageFactory findOneByPersist(boolean $persist) Return the first ChildImageFactory filtered by the persist column
 * @method     ChildImageFactory findOneByImagineLibraryCode(string $imagine_library_code) Return the first ChildImageFactory filtered by the imagine_library_code column
 * @method     ChildImageFactory findOneByImageNotFoundSource(string $image_not_found_source) Return the first ChildImageFactory filtered by the image_not_found_source column
 * @method     ChildImageFactory findOneByImageNotFoundDestinationFileName(string $image_not_found_destination_file_name) Return the first ChildImageFactory filtered by the image_not_found_destination_file_name column
 * @method     ChildImageFactory findOneByDisableI18nProcessing(int $disable_i18n_processing) Return the first ChildImageFactory filtered by the disable_i18n_processing column
 * @method     ChildImageFactory findOneByCreatedAt(string $created_at) Return the first ChildImageFactory filtered by the created_at column
 * @method     ChildImageFactory findOneByUpdatedAt(string $updated_at) Return the first ChildImageFactory filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildImageFactory objects filtered by the id column
 * @method     array findByCode(string $code) Return ChildImageFactory objects filtered by the code column
 * @method     array findBySources(array $sources) Return ChildImageFactory objects filtered by the sources column
 * @method     array findByDestination(string $destination) Return ChildImageFactory objects filtered by the destination column
 * @method     array findByWidth(int $width) Return ChildImageFactory objects filtered by the width column
 * @method     array findByHeight(int $height) Return ChildImageFactory objects filtered by the height column
 * @method     array findByQuality(int $quality) Return ChildImageFactory objects filtered by the quality column
 * @method     array findByBackgroundColor(string $background_color) Return ChildImageFactory objects filtered by the background_color column
 * @method     array findByBackgroundOpacity(int $background_opacity) Return ChildImageFactory objects filtered by the background_opacity column
 * @method     array findByResizeMode(string $resize_mode) Return ChildImageFactory objects filtered by the resize_mode column
 * @method     array findByRotation(int $rotation) Return ChildImageFactory objects filtered by the rotation column
 * @method     array findByResamplingFilter(string $resampling_filter) Return ChildImageFactory objects filtered by the resampling_filter column
 * @method     array findByPrefix(string $prefix) Return ChildImageFactory objects filtered by the prefix column
 * @method     array findBySuffix(string $suffix) Return ChildImageFactory objects filtered by the suffix column
 * @method     array findByLayers(array $layers) Return ChildImageFactory objects filtered by the layers column
 * @method     array findByEffects(array $effects) Return ChildImageFactory objects filtered by the effects column
 * @method     array findByPixelRatios(array $pixel_ratios) Return ChildImageFactory objects filtered by the pixel_ratios column
 * @method     array findByInterlace(string $interlace) Return ChildImageFactory objects filtered by the interlace column
 * @method     array findByPersist(boolean $persist) Return ChildImageFactory objects filtered by the persist column
 * @method     array findByImagineLibraryCode(string $imagine_library_code) Return ChildImageFactory objects filtered by the imagine_library_code column
 * @method     array findByImageNotFoundSource(string $image_not_found_source) Return ChildImageFactory objects filtered by the image_not_found_source column
 * @method     array findByImageNotFoundDestinationFileName(string $image_not_found_destination_file_name) Return ChildImageFactory objects filtered by the image_not_found_destination_file_name column
 * @method     array findByDisableI18nProcessing(int $disable_i18n_processing) Return ChildImageFactory objects filtered by the disable_i18n_processing column
 * @method     array findByCreatedAt(string $created_at) Return ChildImageFactory objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildImageFactory objects filtered by the updated_at column
 *
 */
abstract class ImageFactoryQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ImageFactory\Model\Base\ImageFactoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ImageFactory\\Model\\ImageFactory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildImageFactoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildImageFactoryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ImageFactory\Model\ImageFactoryQuery) {
            return $criteria;
        }
        $query = new \ImageFactory\Model\ImageFactoryQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildImageFactory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ImageFactoryTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ImageFactoryTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildImageFactory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CODE, SOURCES, DESTINATION, WIDTH, HEIGHT, QUALITY, BACKGROUND_COLOR, BACKGROUND_OPACITY, RESIZE_MODE, ROTATION, RESAMPLING_FILTER, PREFIX, SUFFIX, LAYERS, EFFECTS, PIXEL_RATIOS, INTERLACE, PERSIST, IMAGINE_LIBRARY_CODE, IMAGE_NOT_FOUND_SOURCE, IMAGE_NOT_FOUND_DESTINATION_FILE_NAME, DISABLE_I18N_PROCESSING, CREATED_AT, UPDATED_AT FROM image_factory WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildImageFactory();
            $obj->hydrate($row);
            ImageFactoryTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildImageFactory|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ImageFactoryTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ImageFactoryTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the sources column
     *
     * @param     array $sources The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterBySources($sources = null, $comparison = null)
    {
        $key = $this->getAliasedColName(ImageFactoryTableMap::SOURCES);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($sources as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($sources as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($sources as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::SOURCES, $sources, $comparison);
    }

    /**
     * Filter the query on the sources column
     * @param     mixed $sources The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterBySource($sources = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($sources)) {
                $sources = '%| ' . $sources . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $sources = '%| ' . $sources . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(ImageFactoryTableMap::SOURCES);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $sources, $comparison);
            } else {
                $this->addAnd($key, $sources, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::SOURCES, $sources, $comparison);
    }

    /**
     * Filter the query on the destination column
     *
     * Example usage:
     * <code>
     * $query->filterByDestination('fooValue');   // WHERE destination = 'fooValue'
     * $query->filterByDestination('%fooValue%'); // WHERE destination LIKE '%fooValue%'
     * </code>
     *
     * @param     string $destination The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByDestination($destination = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($destination)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $destination)) {
                $destination = str_replace('*', '%', $destination);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::DESTINATION, $destination, $comparison);
    }

    /**
     * Filter the query on the width column
     *
     * Example usage:
     * <code>
     * $query->filterByWidth(1234); // WHERE width = 1234
     * $query->filterByWidth(array(12, 34)); // WHERE width IN (12, 34)
     * $query->filterByWidth(array('min' => 12)); // WHERE width > 12
     * </code>
     *
     * @param     mixed $width The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByWidth($width = null, $comparison = null)
    {
        if (is_array($width)) {
            $useMinMax = false;
            if (isset($width['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::WIDTH, $width['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($width['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::WIDTH, $width['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::WIDTH, $width, $comparison);
    }

    /**
     * Filter the query on the height column
     *
     * Example usage:
     * <code>
     * $query->filterByHeight(1234); // WHERE height = 1234
     * $query->filterByHeight(array(12, 34)); // WHERE height IN (12, 34)
     * $query->filterByHeight(array('min' => 12)); // WHERE height > 12
     * </code>
     *
     * @param     mixed $height The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByHeight($height = null, $comparison = null)
    {
        if (is_array($height)) {
            $useMinMax = false;
            if (isset($height['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::HEIGHT, $height['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($height['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::HEIGHT, $height['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::HEIGHT, $height, $comparison);
    }

    /**
     * Filter the query on the quality column
     *
     * Example usage:
     * <code>
     * $query->filterByQuality(1234); // WHERE quality = 1234
     * $query->filterByQuality(array(12, 34)); // WHERE quality IN (12, 34)
     * $query->filterByQuality(array('min' => 12)); // WHERE quality > 12
     * </code>
     *
     * @param     mixed $quality The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByQuality($quality = null, $comparison = null)
    {
        if (is_array($quality)) {
            $useMinMax = false;
            if (isset($quality['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::QUALITY, $quality['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quality['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::QUALITY, $quality['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::QUALITY, $quality, $comparison);
    }

    /**
     * Filter the query on the background_color column
     *
     * Example usage:
     * <code>
     * $query->filterByBackgroundColor('fooValue');   // WHERE background_color = 'fooValue'
     * $query->filterByBackgroundColor('%fooValue%'); // WHERE background_color LIKE '%fooValue%'
     * </code>
     *
     * @param     string $backgroundColor The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByBackgroundColor($backgroundColor = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($backgroundColor)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $backgroundColor)) {
                $backgroundColor = str_replace('*', '%', $backgroundColor);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::BACKGROUND_COLOR, $backgroundColor, $comparison);
    }

    /**
     * Filter the query on the background_opacity column
     *
     * Example usage:
     * <code>
     * $query->filterByBackgroundOpacity(1234); // WHERE background_opacity = 1234
     * $query->filterByBackgroundOpacity(array(12, 34)); // WHERE background_opacity IN (12, 34)
     * $query->filterByBackgroundOpacity(array('min' => 12)); // WHERE background_opacity > 12
     * </code>
     *
     * @param     mixed $backgroundOpacity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByBackgroundOpacity($backgroundOpacity = null, $comparison = null)
    {
        if (is_array($backgroundOpacity)) {
            $useMinMax = false;
            if (isset($backgroundOpacity['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::BACKGROUND_OPACITY, $backgroundOpacity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($backgroundOpacity['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::BACKGROUND_OPACITY, $backgroundOpacity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::BACKGROUND_OPACITY, $backgroundOpacity, $comparison);
    }

    /**
     * Filter the query on the resize_mode column
     *
     * Example usage:
     * <code>
     * $query->filterByResizeMode('fooValue');   // WHERE resize_mode = 'fooValue'
     * $query->filterByResizeMode('%fooValue%'); // WHERE resize_mode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $resizeMode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByResizeMode($resizeMode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($resizeMode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $resizeMode)) {
                $resizeMode = str_replace('*', '%', $resizeMode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::RESIZE_MODE, $resizeMode, $comparison);
    }

    /**
     * Filter the query on the rotation column
     *
     * Example usage:
     * <code>
     * $query->filterByRotation(1234); // WHERE rotation = 1234
     * $query->filterByRotation(array(12, 34)); // WHERE rotation IN (12, 34)
     * $query->filterByRotation(array('min' => 12)); // WHERE rotation > 12
     * </code>
     *
     * @param     mixed $rotation The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByRotation($rotation = null, $comparison = null)
    {
        if (is_array($rotation)) {
            $useMinMax = false;
            if (isset($rotation['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::ROTATION, $rotation['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($rotation['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::ROTATION, $rotation['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::ROTATION, $rotation, $comparison);
    }

    /**
     * Filter the query on the resampling_filter column
     *
     * Example usage:
     * <code>
     * $query->filterByResamplingFilter('fooValue');   // WHERE resampling_filter = 'fooValue'
     * $query->filterByResamplingFilter('%fooValue%'); // WHERE resampling_filter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $resamplingFilter The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByResamplingFilter($resamplingFilter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($resamplingFilter)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $resamplingFilter)) {
                $resamplingFilter = str_replace('*', '%', $resamplingFilter);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::RESAMPLING_FILTER, $resamplingFilter, $comparison);
    }

    /**
     * Filter the query on the prefix column
     *
     * Example usage:
     * <code>
     * $query->filterByPrefix('fooValue');   // WHERE prefix = 'fooValue'
     * $query->filterByPrefix('%fooValue%'); // WHERE prefix LIKE '%fooValue%'
     * </code>
     *
     * @param     string $prefix The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByPrefix($prefix = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prefix)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $prefix)) {
                $prefix = str_replace('*', '%', $prefix);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::PREFIX, $prefix, $comparison);
    }

    /**
     * Filter the query on the suffix column
     *
     * Example usage:
     * <code>
     * $query->filterBySuffix('fooValue');   // WHERE suffix = 'fooValue'
     * $query->filterBySuffix('%fooValue%'); // WHERE suffix LIKE '%fooValue%'
     * </code>
     *
     * @param     string $suffix The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterBySuffix($suffix = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($suffix)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $suffix)) {
                $suffix = str_replace('*', '%', $suffix);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::SUFFIX, $suffix, $comparison);
    }

    /**
     * Filter the query on the layers column
     *
     * @param     array $layers The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByLayers($layers = null, $comparison = null)
    {
        $key = $this->getAliasedColName(ImageFactoryTableMap::LAYERS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($layers as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($layers as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($layers as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::LAYERS, $layers, $comparison);
    }

    /**
     * Filter the query on the layers column
     * @param     mixed $layers The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByLayer($layers = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($layers)) {
                $layers = '%| ' . $layers . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $layers = '%| ' . $layers . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(ImageFactoryTableMap::LAYERS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $layers, $comparison);
            } else {
                $this->addAnd($key, $layers, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::LAYERS, $layers, $comparison);
    }

    /**
     * Filter the query on the effects column
     *
     * @param     array $effects The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByEffects($effects = null, $comparison = null)
    {
        $key = $this->getAliasedColName(ImageFactoryTableMap::EFFECTS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($effects as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($effects as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($effects as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::EFFECTS, $effects, $comparison);
    }

    /**
     * Filter the query on the effects column
     * @param     mixed $effects The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByEffect($effects = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($effects)) {
                $effects = '%| ' . $effects . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $effects = '%| ' . $effects . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(ImageFactoryTableMap::EFFECTS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $effects, $comparison);
            } else {
                $this->addAnd($key, $effects, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::EFFECTS, $effects, $comparison);
    }

    /**
     * Filter the query on the pixel_ratios column
     *
     * @param     array $pixelRatios The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByPixelRatios($pixelRatios = null, $comparison = null)
    {
        $key = $this->getAliasedColName(ImageFactoryTableMap::PIXEL_RATIOS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($pixelRatios as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($pixelRatios as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($pixelRatios as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::PIXEL_RATIOS, $pixelRatios, $comparison);
    }

    /**
     * Filter the query on the pixel_ratios column
     * @param     mixed $pixelRatios The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByPixelRatio($pixelRatios = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($pixelRatios)) {
                $pixelRatios = '%| ' . $pixelRatios . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $pixelRatios = '%| ' . $pixelRatios . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(ImageFactoryTableMap::PIXEL_RATIOS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $pixelRatios, $comparison);
            } else {
                $this->addAnd($key, $pixelRatios, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::PIXEL_RATIOS, $pixelRatios, $comparison);
    }

    /**
     * Filter the query on the interlace column
     *
     * Example usage:
     * <code>
     * $query->filterByInterlace('fooValue');   // WHERE interlace = 'fooValue'
     * $query->filterByInterlace('%fooValue%'); // WHERE interlace LIKE '%fooValue%'
     * </code>
     *
     * @param     string $interlace The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByInterlace($interlace = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($interlace)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $interlace)) {
                $interlace = str_replace('*', '%', $interlace);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::INTERLACE, $interlace, $comparison);
    }

    /**
     * Filter the query on the persist column
     *
     * Example usage:
     * <code>
     * $query->filterByPersist(true); // WHERE persist = true
     * $query->filterByPersist('yes'); // WHERE persist = true
     * </code>
     *
     * @param     boolean|string $persist The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByPersist($persist = null, $comparison = null)
    {
        if (is_string($persist)) {
            $persist = in_array(strtolower($persist), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(ImageFactoryTableMap::PERSIST, $persist, $comparison);
    }

    /**
     * Filter the query on the imagine_library_code column
     *
     * Example usage:
     * <code>
     * $query->filterByImagineLibraryCode('fooValue');   // WHERE imagine_library_code = 'fooValue'
     * $query->filterByImagineLibraryCode('%fooValue%'); // WHERE imagine_library_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imagineLibraryCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByImagineLibraryCode($imagineLibraryCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imagineLibraryCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imagineLibraryCode)) {
                $imagineLibraryCode = str_replace('*', '%', $imagineLibraryCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::IMAGINE_LIBRARY_CODE, $imagineLibraryCode, $comparison);
    }

    /**
     * Filter the query on the image_not_found_source column
     *
     * Example usage:
     * <code>
     * $query->filterByImageNotFoundSource('fooValue');   // WHERE image_not_found_source = 'fooValue'
     * $query->filterByImageNotFoundSource('%fooValue%'); // WHERE image_not_found_source LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageNotFoundSource The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByImageNotFoundSource($imageNotFoundSource = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageNotFoundSource)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imageNotFoundSource)) {
                $imageNotFoundSource = str_replace('*', '%', $imageNotFoundSource);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::IMAGE_NOT_FOUND_SOURCE, $imageNotFoundSource, $comparison);
    }

    /**
     * Filter the query on the image_not_found_destination_file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByImageNotFoundDestinationFileName('fooValue');   // WHERE image_not_found_destination_file_name = 'fooValue'
     * $query->filterByImageNotFoundDestinationFileName('%fooValue%'); // WHERE image_not_found_destination_file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageNotFoundDestinationFileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByImageNotFoundDestinationFileName($imageNotFoundDestinationFileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageNotFoundDestinationFileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imageNotFoundDestinationFileName)) {
                $imageNotFoundDestinationFileName = str_replace('*', '%', $imageNotFoundDestinationFileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::IMAGE_NOT_FOUND_DESTINATION_FILE_NAME, $imageNotFoundDestinationFileName, $comparison);
    }

    /**
     * Filter the query on the disable_i18n_processing column
     *
     * Example usage:
     * <code>
     * $query->filterByDisableI18nProcessing(1234); // WHERE disable_i18n_processing = 1234
     * $query->filterByDisableI18nProcessing(array(12, 34)); // WHERE disable_i18n_processing IN (12, 34)
     * $query->filterByDisableI18nProcessing(array('min' => 12)); // WHERE disable_i18n_processing > 12
     * </code>
     *
     * @param     mixed $disableI18nProcessing The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByDisableI18nProcessing($disableI18nProcessing = null, $comparison = null)
    {
        if (is_array($disableI18nProcessing)) {
            $useMinMax = false;
            if (isset($disableI18nProcessing['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::DISABLE_I18N_PROCESSING, $disableI18nProcessing['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($disableI18nProcessing['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::DISABLE_I18N_PROCESSING, $disableI18nProcessing['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::DISABLE_I18N_PROCESSING, $disableI18nProcessing, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ImageFactoryTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ImageFactoryTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ImageFactoryTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \ImageFactory\Model\ImageFactoryI18n object
     *
     * @param \ImageFactory\Model\ImageFactoryI18n|ObjectCollection $imageFactoryI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function filterByImageFactoryI18n($imageFactoryI18n, $comparison = null)
    {
        if ($imageFactoryI18n instanceof \ImageFactory\Model\ImageFactoryI18n) {
            return $this
                ->addUsingAlias(ImageFactoryTableMap::ID, $imageFactoryI18n->getId(), $comparison);
        } elseif ($imageFactoryI18n instanceof ObjectCollection) {
            return $this
                ->useImageFactoryI18nQuery()
                ->filterByPrimaryKeys($imageFactoryI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByImageFactoryI18n() only accepts arguments of type \ImageFactory\Model\ImageFactoryI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ImageFactoryI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function joinImageFactoryI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ImageFactoryI18n');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ImageFactoryI18n');
        }

        return $this;
    }

    /**
     * Use the ImageFactoryI18n relation ImageFactoryI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ImageFactory\Model\ImageFactoryI18nQuery A secondary query class using the current class as primary query
     */
    public function useImageFactoryI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinImageFactoryI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ImageFactoryI18n', '\ImageFactory\Model\ImageFactoryI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildImageFactory $imageFactory Object to remove from the list of results
     *
     * @return ChildImageFactoryQuery The current query, for fluid interface
     */
    public function prune($imageFactory = null)
    {
        if ($imageFactory) {
            $this->addUsingAlias(ImageFactoryTableMap::ID, $imageFactory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the image_factory table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageFactoryTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ImageFactoryTableMap::clearInstancePool();
            ImageFactoryTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildImageFactory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildImageFactory object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ImageFactoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ImageFactoryTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ImageFactoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ImageFactoryTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildImageFactoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ImageFactoryTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildImageFactoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ImageFactoryTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildImageFactoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ImageFactoryTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildImageFactoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ImageFactoryTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildImageFactoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ImageFactoryTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildImageFactoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ImageFactoryTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildImageFactoryQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'ImageFactoryI18n';

        return $this
            ->joinImageFactoryI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildImageFactoryQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('ImageFactoryI18n');
        $this->with['ImageFactoryI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildImageFactoryI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ImageFactoryI18n', '\ImageFactory\Model\ImageFactoryI18nQuery');
    }

} // ImageFactoryQuery
